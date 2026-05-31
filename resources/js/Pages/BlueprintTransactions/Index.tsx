import {
    createContext,
    PropsWithChildren,
    useCallback,
    useContext,
    useEffect,
    useMemo,
    useRef,
    useState,
} from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import {
    PlusIcon,
    PencilSquareIcon,
    TrashIcon,
    BoltIcon,
    MagnifyingGlassIcon,
    FolderIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronDoubleDownIcon,
    ChevronDoubleUpIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import clsx from 'clsx';
import { Transition } from '@headlessui/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PageHeader from '@/Components/PageHeader';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import Select from '@/Components/Select';
import FormField from '@/Components/FormField';
import ConfirmModal from '@/Components/ConfirmModal';
import TransactionFormModal from '@/Components/TransactionFormModal';
import { MonthYear, Transaction, Wallet } from '@/types';

type BlueprintRow = Transaction & {
    category?: { id: number; name: string };
};

type CategoryNode = {
    id: number;
    name: string;
    parent_id?: number | null;
    blueprint_transactions: BlueprintRow[];
    children: CategoryNode[];
};

type Filters = { name?: string; direction?: string; category_id?: string };

type Props = {
    rootCategories: CategoryNode[];
    filters: Filters;
    options: {
        categories: { id: number; name: string }[];
        wallets: Pick<Wallet, 'id' | 'name'>[];
        month_years: Pick<MonthYear, 'id' | 'month' | 'year'>[];
    };
};

const EXPANSION_KEY = 'blueprints.expanded';

const fmt = (n: number | string) =>
    new Intl.NumberFormat('en-EG', { minimumFractionDigits: 2 }).format(Number(n) || 0);

// ───── Expansion context ────────────────────────────────────────────
type ExpansionApi = {
    isExpanded: (id: number) => boolean;
    toggle: (id: number) => void;
    setMany: (ids: number[], expanded: boolean) => void;
    clear: () => void;
};

const ExpansionContext = createContext<ExpansionApi | null>(null);

function readExpansion(): Set<number> {
    if (typeof window === 'undefined') return new Set();
    try {
        const raw = window.localStorage.getItem(EXPANSION_KEY);
        if (!raw) return new Set();
        const arr = JSON.parse(raw);
        return new Set(Array.isArray(arr) ? arr.map(Number).filter(Number.isFinite) : []);
    } catch {
        return new Set();
    }
}

function ExpansionProvider({ children }: PropsWithChildren) {
    const [expanded, setExpanded] = useState<Set<number>>(() => readExpansion());

    const persist = useCallback((next: Set<number>) => {
        try {
            window.localStorage.setItem(EXPANSION_KEY, JSON.stringify(Array.from(next)));
        } catch {
            /* ignore */
        }
    }, []);

    const api: ExpansionApi = useMemo(() => ({
        isExpanded: (id) => expanded.has(id),
        toggle: (id) => {
            setExpanded((prev) => {
                const next = new Set(prev);
                if (next.has(id)) next.delete(id);
                else next.add(id);
                persist(next);
                return next;
            });
        },
        setMany: (ids, on) => {
            setExpanded((prev) => {
                const next = new Set(prev);
                ids.forEach((id) => (on ? next.add(id) : next.delete(id)));
                persist(next);
                return next;
            });
        },
        clear: () => {
            persist(new Set());
            setExpanded(new Set());
        },
    }), [expanded, persist]);

    return <ExpansionContext.Provider value={api}>{children}</ExpansionContext.Provider>;
}

function useExpansion() {
    const ctx = useContext(ExpansionContext);
    if (!ctx) throw new Error('useExpansion outside provider');
    return ctx;
}

// ───── Helpers ──────────────────────────────────────────────────────
function walkIds(nodes: CategoryNode[]): number[] {
    const out: number[] = [];
    const visit = (n: CategoryNode) => {
        out.push(n.id);
        n.children.forEach(visit);
    };
    nodes.forEach(visit);
    return out;
}

type Totals = { count: number; net: number };

function aggregate(node: CategoryNode): Totals {
    let count = 0;
    let net = 0;
    const visit = (n: CategoryNode) => {
        for (const t of n.blueprint_transactions ?? []) {
            count += 1;
            const amount = Number(t.price) * Number(t.quantity);
            net += t.direction === 'credit' ? amount : -amount;
        }
        n.children.forEach(visit);
    };
    visit(node);
    return { count, net };
}

function useDebouncedEffect(fn: () => void, deps: unknown[], delay: number) {
    useEffect(() => {
        const t = setTimeout(fn, delay);
        return () => clearTimeout(t);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, deps);
}

// ───── Page ─────────────────────────────────────────────────────────
export default function BlueprintIndex(props: Props) {
    return (
        <ExpansionProvider>
            <BlueprintPage {...props} />
        </ExpansionProvider>
    );
}

function BlueprintPage({ rootCategories, filters, options }: Props) {
    const expansion = useExpansion();

    const [filtersOpen, setFiltersOpen] = useState(false);
    const [formOpen, setFormOpen] = useState<
        | { mode: 'create'; categoryId?: number }
        | { mode: 'edit'; row: BlueprintRow }
        | null
    >(null);
    const [deleteTarget, setDeleteTarget] = useState<BlueprintRow | null>(null);
    const [addTarget, setAddTarget] = useState<BlueprintRow | null>(null);

    const filterForm = useForm({
        name: filters.name ?? '',
        direction: filters.direction ?? '',
        category_id: filters.category_id ?? '',
    });

    const hasActiveFilters = Object.values(filterForm.data).some(Boolean);

    // Live search on name (debounced server-side filter)
    useDebouncedEffect(
        () => {
            if (filterForm.data.name === (filters.name ?? '')) return;
            router.get('/blueprint_transactions', filterForm.data, {
                preserveState: true,
                preserveScroll: true,
                only: ['rootCategories', 'filters'],
                replace: true,
            });
        },
        [filterForm.data.name],
        300,
    );

    const apply = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/blueprint_transactions', filterForm.data, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilter = (key: keyof Filters) => {
        const next = { ...filterForm.data, [key]: '' };
        filterForm.setData(next);
        router.get('/blueprint_transactions', next, { preserveState: true, preserveScroll: true });
    };

    const clearAll = () => {
        filterForm.reset();
        router.get('/blueprint_transactions', {}, { preserveState: true, preserveScroll: true });
    };

    const searchRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (filtersOpen) {
            const t = setTimeout(() => searchRef.current?.focus(), 50);
            return () => clearTimeout(t);
        }
    }, [filtersOpen]);

    // Auto-expand matching branches while searching server-side
    useEffect(() => {
        if (filters.name || filters.direction || filters.category_id) {
            expansion.setMany(walkIds(rootCategories), true);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [filters.name, filters.direction, filters.category_id, rootCategories]);

    // Keyboard shortcuts
    useEffect(() => {
        const onKey = (e: KeyboardEvent) => {
            const target = e.target as HTMLElement | null;
            const tag = target?.tagName?.toLowerCase();
            if (tag === 'input' || tag === 'textarea' || target?.isContentEditable) return;
            if (e.metaKey || e.ctrlKey || e.altKey) return;

            if (e.key === 'n') {
                e.preventDefault();
                setFormOpen({ mode: 'create' });
            } else if (e.key === '/') {
                e.preventDefault();
                setFiltersOpen(true);
            }
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, []);

    const expandAll = () => expansion.setMany(walkIds(rootCategories), true);
    const collapseAll = () => expansion.clear();

    const directionLabel = (d: string) => (d === 'credit' ? 'Credit' : d === 'debit' ? 'Debit' : d);
    const categoryLabel = (id: string) => options.categories.find((c) => String(c.id) === id)?.name ?? id;

    return (
        <AuthenticatedLayout>
            <Head title="Blueprint Transactions" />

            <PageHeader
                title="Blueprint Transactions"
                description="Recurring templates for quick entry"
                actions={
                    <>
                        <IconButton onClick={expandAll} title="Expand all">
                            <ChevronDoubleDownIcon className="h-4 w-4" />
                        </IconButton>
                        <IconButton onClick={collapseAll} title="Collapse all">
                            <ChevronDoubleUpIcon className="h-4 w-4" />
                        </IconButton>
                        <SecondaryButton onClick={() => setFiltersOpen((v) => !v)}>
                            <MagnifyingGlassIcon className="mr-1.5 h-4 w-4" /> Filters
                        </SecondaryButton>
                        <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                            <PlusIcon className="mr-1.5 h-4 w-4" /> Add Blueprint
                        </PrimaryButton>
                    </>
                }
            />

            {hasActiveFilters && (
                <div className="mb-4 flex flex-wrap items-center gap-2">
                    {filterForm.data.name && (
                        <FilterChip label={`Name: ${filterForm.data.name}`} onClear={() => clearFilter('name')} />
                    )}
                    {filterForm.data.direction && (
                        <FilterChip label={`Direction: ${directionLabel(filterForm.data.direction)}`} onClear={() => clearFilter('direction')} />
                    )}
                    {filterForm.data.category_id && (
                        <FilterChip label={`Category: ${categoryLabel(filterForm.data.category_id)}`} onClear={() => clearFilter('category_id')} />
                    )}
                    <button
                        type="button"
                        onClick={clearAll}
                        className="text-xs text-muted-foreground transition-colors hover:text-foreground"
                    >
                        Clear all
                    </button>
                </div>
            )}

            {filtersOpen && (
                <form onSubmit={apply} className="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-border bg-card p-4 sm:grid-cols-4">
                    <FormField label="Name">
                        <TextInput
                            ref={searchRef as unknown as React.Ref<HTMLInputElement>}
                            value={filterForm.data.name}
                            onChange={(e) => filterForm.setData('name', e.target.value)}
                            placeholder="Search blueprints…"
                        />
                    </FormField>
                    <FormField label="Direction">
                        <Select
                            value={filterForm.data.direction}
                            onChange={(e) => filterForm.setData('direction', e.target.value)}
                            placeholder="All"
                            options={[
                                { value: 'credit', label: 'Credit' },
                                { value: 'debit', label: 'Debit' },
                            ]}
                        />
                    </FormField>
                    <FormField label="Category">
                        <Select
                            value={filterForm.data.category_id}
                            onChange={(e) => filterForm.setData('category_id', e.target.value)}
                            placeholder="All"
                            options={options.categories.map((c) => ({ value: c.id, label: c.name }))}
                        />
                    </FormField>
                    <div className="flex items-end">
                        <PrimaryButton>Apply</PrimaryButton>
                    </div>
                </form>
            )}

            <div className="space-y-2">
                {rootCategories.length === 0 ? (
                    hasActiveFilters ? (
                        <EmptyState
                            title="No blueprints match your filters"
                            description="Adjust or clear the filters to see more results."
                            cta={<SecondaryButton onClick={clearAll}>Clear filters</SecondaryButton>}
                        />
                    ) : (
                        <EmptyState
                            title="No blueprints yet"
                            description="Blueprints are reusable templates for recurring transactions."
                            cta={
                                <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                                    <PlusIcon className="mr-1.5 h-4 w-4" /> Add your first blueprint
                                </PrimaryButton>
                            }
                        />
                    )
                ) : (
                    rootCategories.map((cat) => (
                        <CategoryBranch
                            key={cat.id}
                            node={cat}
                            onEdit={(row) => setFormOpen({ mode: 'edit', row })}
                            onDelete={(row) => setDeleteTarget(row)}
                            onAdd={(row) => setAddTarget(row)}
                            onCreateUnderCategory={(catId) => setFormOpen({ mode: 'create', categoryId: catId })}
                        />
                    ))
                )}
            </div>

            <TransactionFormModal
                show={!!formOpen}
                onClose={() => setFormOpen(null)}
                scope="blueprint"
                mode={formOpen?.mode ?? 'create'}
                transaction={
                    formOpen?.mode === 'edit'
                        ? formOpen.row
                        : formOpen?.mode === 'create' && formOpen.categoryId
                          ? ({ category_id: formOpen.categoryId } as Partial<Transaction>)
                          : undefined
                }
                endpoint={
                    formOpen?.mode === 'edit'
                        ? `/blueprint_transactions/${formOpen.row.id}`
                        : '/blueprint_transactions'
                }
                categories={options.categories}
            />

            <ConfirmModal
                show={!!deleteTarget}
                title="Delete blueprint?"
                message={deleteTarget ? `Delete "${deleteTarget.name}"?` : ''}
                onClose={() => setDeleteTarget(null)}
                onConfirm={() => {
                    if (!deleteTarget) return;
                    router.delete(`/blueprint_transactions/${deleteTarget.id}`, {
                        preserveScroll: true,
                        onFinish: () => setDeleteTarget(null),
                    });
                }}
            />

            <TransactionFormModal
                show={!!addTarget}
                onClose={() => setAddTarget(null)}
                scope="normal"
                mode="edit"
                transaction={addTarget ?? undefined}
                endpoint="/blueprint_transactions/update_and_add_transaction"
                method="post"
                categories={options.categories}
                wallets={options.wallets}
                monthYears={options.month_years}
                title="Update & add as transaction"
                extraData={addTarget ? { id: addTarget.id } : undefined}
            />
        </AuthenticatedLayout>
    );
}

// ───── Category branch ──────────────────────────────────────────────
function CategoryBranch({
    node,
    depth = 0,
    onEdit,
    onDelete,
    onAdd,
    onCreateUnderCategory,
}: {
    node: CategoryNode;
    depth?: number;
    onEdit: (row: BlueprintRow) => void;
    onDelete: (row: BlueprintRow) => void;
    onAdd: (row: BlueprintRow) => void;
    onCreateUnderCategory: (categoryId: number) => void;
}) {
    const { isExpanded, toggle } = useExpansion();
    const open = isExpanded(node.id);

    const hasTransactions = node.blueprint_transactions && node.blueprint_transactions.length > 0;
    const hasChildren = node.children && node.children.length > 0;
    if (!hasTransactions && !hasChildren) return null;

    const totals = useMemo(() => aggregate(node), [node]);

    return (
        <div
            className="rounded-xl border border-border bg-card shadow-sm transition-colors hover:border-border/80"
            style={{ marginLeft: depth * 16 }}
        >
            <button
                type="button"
                onClick={() => toggle(node.id)}
                className="group flex w-full items-center gap-2 rounded-t-xl px-4 py-3 text-left transition-colors hover:bg-muted/40"
            >
                {open ? (
                    <ChevronDownIcon className="h-4 w-4 text-muted-foreground" />
                ) : (
                    <ChevronRightIcon className="h-4 w-4 text-muted-foreground" />
                )}
                <FolderIcon className="h-4 w-4 text-primary" />
                <span className="font-semibold text-foreground">{node.name}</span>

                <span className="ml-auto flex items-center gap-3">
                    {!open && totals.count > 0 && (
                        <>
                            <span className="hidden rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground sm:inline-block">
                                {totals.count} {totals.count === 1 ? 'blueprint' : 'blueprints'}
                            </span>
                            <span
                                className={clsx(
                                    'hidden rounded-md px-2 py-0.5 text-xs font-semibold sm:inline-block',
                                    totals.net >= 0 ? 'bg-success/10 text-success' : 'bg-destructive/10 text-destructive',
                                )}
                            >
                                {totals.net >= 0 ? '+' : '-'}E£ {fmt(Math.abs(totals.net))}
                            </span>
                        </>
                    )}
                </span>
            </button>

            <Transition
                show={open}
                enter="transition-all duration-200 ease-out overflow-hidden"
                enterFrom="max-h-0 opacity-0"
                enterTo="max-h-[4000px] opacity-100"
                leave="transition-all duration-150 ease-in overflow-hidden"
                leaveFrom="max-h-[4000px] opacity-100"
                leaveTo="max-h-0 opacity-0"
            >
                <div className="border-t border-border px-4 pb-3 pt-2">
                    {hasTransactions && (
                        <ul className="divide-y divide-border">
                            {node.blueprint_transactions.map((t) => {
                                const total = Number(t.price) * Number(t.quantity);
                                return (
                                    <li
                                        key={t.id}
                                        className="group flex items-center gap-3 py-2"
                                    >
                                        <span className="min-w-0 flex-1 truncate text-sm font-medium text-foreground">
                                            {t.name}
                                        </span>
                                        {t.quantity > 1 && (
                                            <span className="hidden font-mono text-xs text-muted-foreground sm:inline">
                                                ×{Number(t.quantity)}
                                            </span>
                                        )}
                                        <span
                                            className={clsx(
                                                'font-semibold tabular-nums',
                                                t.direction === 'credit' ? 'text-success' : 'text-destructive',
                                            )}
                                        >
                                            {t.direction === 'credit' ? '+' : '-'}E£ {fmt(total)}
                                        </span>
                                        <div className="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100 pointer-coarse:opacity-100">
                                            <IconButton onClick={() => onAdd(t)} title="Use to create a transaction" tone="warning">
                                                <BoltIcon className="h-4 w-4" />
                                            </IconButton>
                                            <IconButton onClick={() => onEdit(t)} title="Edit">
                                                <PencilSquareIcon className="h-4 w-4" />
                                            </IconButton>
                                            <IconButton onClick={() => onDelete(t)} title="Delete" danger>
                                                <TrashIcon className="h-4 w-4" />
                                            </IconButton>
                                        </div>
                                    </li>
                                );
                            })}
                        </ul>
                    )}

                    {hasTransactions && (
                        <button
                            type="button"
                            onClick={() => onCreateUnderCategory(node.id)}
                            className="mt-2 inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-primary"
                            title="Add a blueprint in this category"
                        >
                            <PlusIcon className="h-3.5 w-3.5" /> Add blueprint here
                        </button>
                    )}

                    {hasChildren && (
                        <div className="mt-2 space-y-2">
                            {node.children.map((child) => (
                                <CategoryBranch
                                    key={child.id}
                                    node={child}
                                    depth={depth + 1}
                                    onEdit={onEdit}
                                    onDelete={onDelete}
                                    onAdd={onAdd}
                                    onCreateUnderCategory={onCreateUnderCategory}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </Transition>
        </div>
    );
}

// ───── UI primitives ────────────────────────────────────────────────
function FilterChip({ label, onClear }: { label: string; onClear: () => void }) {
    return (
        <span className="inline-flex items-center gap-1 rounded-full border border-border bg-card px-2.5 py-1 text-xs font-medium text-foreground">
            {label}
            <button
                type="button"
                onClick={onClear}
                className="rounded-full text-muted-foreground hover:text-destructive"
                title="Remove filter"
            >
                <XMarkIcon className="h-3.5 w-3.5" />
            </button>
        </span>
    );
}

function IconButton({
    children,
    onClick,
    title,
    danger,
    tone,
}: {
    children: React.ReactNode;
    onClick: () => void;
    title: string;
    danger?: boolean;
    tone?: 'warning';
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            title={title}
            className={clsx(
                'rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted',
                danger && 'hover:text-destructive',
                tone === 'warning' && 'hover:text-warning',
                !danger && tone !== 'warning' && 'hover:text-primary',
            )}
        >
            {children}
        </button>
    );
}

function EmptyState({
    title,
    description,
    cta,
}: {
    title: string;
    description: string;
    cta: React.ReactNode;
}) {
    return (
        <div className="rounded-xl border border-border bg-card p-10 text-center">
            <div className="mx-auto flex max-w-sm flex-col items-center gap-3">
                <div className="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                    <BoltIcon className="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 className="text-base font-semibold text-foreground">{title}</h3>
                <p className="text-sm text-muted-foreground">{description}</p>
                {cta}
            </div>
        </div>
    );
}
