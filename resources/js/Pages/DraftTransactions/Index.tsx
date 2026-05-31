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
    CheckCircleIcon,
    MagnifyingGlassIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronDoubleDownIcon,
    ChevronDoubleUpIcon,
    XMarkIcon,
    CalendarIcon,
    WalletIcon,
    TagIcon,
    UserIcon,
    InboxIcon,
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
import Pagination from '@/Components/Pagination';
import ConfirmModal from '@/Components/ConfirmModal';
import TransactionFormModal from '@/Components/TransactionFormModal';
import { MonthYear, Paginator, Transaction, Wallet } from '@/types';

type Row = Transaction & {
    category?: { id: number; name: string };
    wallet?: Pick<Wallet, 'id' | 'name'>;
    month_year?: Pick<MonthYear, 'id' | 'month' | 'year'>;
    user?: { first_name: string; last_name: string };
};

type Filters = {
    name?: string;
    direction?: string;
    category_id?: string;
    wallet_id?: string;
    month?: string;
    year?: string;
};

type Props = {
    transactions: Paginator<Row>;
    filters: Filters;
    options: {
        categories: { id: number; name: string }[];
        wallets: Pick<Wallet, 'id' | 'name'>[];
        month_years: Pick<MonthYear, 'id' | 'month' | 'year'>[];
        years: number[];
    };
};

const EXPANSION_KEY = 'drafts.expanded';

const fmt = (n: number | string) =>
    new Intl.NumberFormat('en-EG', { minimumFractionDigits: 2 }).format(Number(n) || 0);

const MONTH_NAMES = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December',
];

// month_years.month is stored zero-padded ("01".."12"), so the filter value must match.
const MONTH_OPTIONS = MONTH_NAMES.map((name, i) => ({ value: String(i + 1).padStart(2, '0'), label: name }));

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

function useDebouncedEffect(fn: () => void, deps: unknown[], delay: number) {
    useEffect(() => {
        const t = setTimeout(fn, delay);
        return () => clearTimeout(t);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, deps);
}

// ───── Page ─────────────────────────────────────────────────────────
export default function DraftIndex(props: Props) {
    return (
        <ExpansionProvider>
            <DraftsPage {...props} />
        </ExpansionProvider>
    );
}

function DraftsPage({ transactions, filters, options }: Props) {
    const expansion = useExpansion();

    const [filtersOpen, setFiltersOpen] = useState(false);
    const [formOpen, setFormOpen] = useState<
        | { mode: 'create' }
        | { mode: 'edit'; row: Row }
        | null
    >(null);
    const [deleteTarget, setDeleteTarget] = useState<Row | null>(null);
    const [promoteTarget, setPromoteTarget] = useState<Row | null>(null);

    const filterForm = useForm({
        name: filters.name ?? '',
        direction: filters.direction ?? '',
        category_id: filters.category_id ?? '',
        wallet_id: filters.wallet_id ?? '',
        month: filters.month ?? '',
        year: filters.year ?? '',
    });

    const hasActiveFilters = Object.values(filterForm.data).some(Boolean);

    // Live search on name (debounced)
    useDebouncedEffect(
        () => {
            if (filterForm.data.name === (filters.name ?? '')) return;
            router.get('/draft_transactions', filterForm.data, {
                preserveState: true,
                preserveScroll: true,
                only: ['transactions', 'filters'],
                replace: true,
            });
        },
        [filterForm.data.name],
        300,
    );

    const apply = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/draft_transactions', filterForm.data, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilter = (key: keyof Filters) => {
        const next = { ...filterForm.data, [key]: '' };
        filterForm.setData(next);
        router.get('/draft_transactions', next, { preserveState: true, preserveScroll: true });
    };

    const clearAll = () => {
        filterForm.reset();
        router.get('/draft_transactions', {}, { preserveState: true, preserveScroll: true });
    };

    const searchRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (filtersOpen) {
            const t = setTimeout(() => searchRef.current?.focus(), 50);
            return () => clearTimeout(t);
        }
    }, [filtersOpen]);

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

    const expandAll = () => expansion.setMany(transactions.data.map((t) => t.id), true);
    const collapseAll = () => expansion.clear();

    // Per-page summary
    const pageSummary = useMemo(() => {
        let net = 0;
        for (const t of transactions.data) {
            const amt = Number(t.price) * Number(t.quantity);
            net += t.direction === 'credit' ? amt : -amt;
        }
        return { count: transactions.data.length, net };
    }, [transactions.data]);

    const directionLabel = (d: string) => (d === 'credit' ? 'Credit' : d === 'debit' ? 'Debit' : d);
    const categoryLabel = (id: string) => options.categories.find((c) => String(c.id) === id)?.name ?? id;
    const walletLabel = (id: string) => options.wallets.find((w) => String(w.id) === id)?.name ?? id;

    return (
        <AuthenticatedLayout>
            <Head title="Draft Transactions" />

            <PageHeader
                title="Draft Transactions"
                description="Transactions you haven't finalized yet"
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
                            <PlusIcon className="mr-1.5 h-4 w-4" /> Add Draft
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
                    {filterForm.data.wallet_id && (
                        <FilterChip label={`Wallet: ${walletLabel(filterForm.data.wallet_id)}`} onClear={() => clearFilter('wallet_id')} />
                    )}
                    {filterForm.data.year && (
                        <FilterChip label={`Year: ${filterForm.data.year}`} onClear={() => clearFilter('year')} />
                    )}
                    {filterForm.data.month && (
                        <FilterChip
                            label={`Month: ${MONTH_NAMES[Number(filterForm.data.month) - 1] ?? filterForm.data.month}`}
                            onClear={() => clearFilter('month')}
                        />
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
                <form
                    onSubmit={apply}
                    className="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-border bg-card p-4 sm:grid-cols-3 lg:grid-cols-7"
                >
                    <FormField label="Name">
                        <TextInput
                            ref={searchRef as unknown as React.Ref<HTMLInputElement>}
                            value={filterForm.data.name}
                            onChange={(e) => filterForm.setData('name', e.target.value)}
                            placeholder="Search…"
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
                    <FormField label="Wallet">
                        <Select
                            value={filterForm.data.wallet_id}
                            onChange={(e) => filterForm.setData('wallet_id', e.target.value)}
                            placeholder="All"
                            options={options.wallets.map((w) => ({ value: w.id, label: w.name }))}
                        />
                    </FormField>
                    <FormField label="Month">
                        <Select
                            value={filterForm.data.month}
                            onChange={(e) => filterForm.setData('month', e.target.value)}
                            placeholder="All"
                            options={MONTH_OPTIONS}
                        />
                    </FormField>
                    <FormField label="Year">
                        <Select
                            value={filterForm.data.year}
                            onChange={(e) => filterForm.setData('year', e.target.value)}
                            placeholder="All"
                            options={options.years.map((y) => ({ value: y, label: String(y) }))}
                        />
                    </FormField>
                    <div className="flex items-end">
                        <PrimaryButton>Apply</PrimaryButton>
                    </div>
                </form>
            )}

            {/* Page summary */}
            {transactions.data.length > 0 && (
                <div className="mb-3 flex items-center justify-between text-xs text-muted-foreground">
                    <span>
                        Showing {transactions.from ?? 0}–{transactions.to ?? 0} of {transactions.total} drafts
                    </span>
                    <span>
                        Page net:{' '}
                        <span
                            className={clsx(
                                'font-semibold tabular-nums',
                                pageSummary.net >= 0 ? 'text-success' : 'text-destructive',
                            )}
                        >
                            {pageSummary.net >= 0 ? '+' : '-'}E£ {fmt(Math.abs(pageSummary.net))}
                        </span>
                    </span>
                </div>
            )}

            <div className="space-y-2">
                {transactions.data.length === 0 ? (
                    hasActiveFilters ? (
                        <EmptyState
                            title="No drafts match your filters"
                            description="Adjust or clear the filters to see more results."
                            cta={<SecondaryButton onClick={clearAll}>Clear filters</SecondaryButton>}
                        />
                    ) : (
                        <EmptyState
                            title="No drafts yet"
                            description="Drafts let you stage a transaction before committing it as normal."
                            cta={
                                <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                                    <PlusIcon className="mr-1.5 h-4 w-4" /> Add your first draft
                                </PrimaryButton>
                            }
                        />
                    )
                ) : (
                    transactions.data.map((t) => (
                        <DraftRow
                            key={t.id}
                            row={t}
                            onEdit={() => setFormOpen({ mode: 'edit', row: t })}
                            onDelete={() => setDeleteTarget(t)}
                            onPromote={() => setPromoteTarget(t)}
                        />
                    ))
                )}
            </div>

            <div className="mt-4">
                <Pagination links={transactions.links} />
            </div>

            <TransactionFormModal
                show={!!formOpen}
                onClose={() => setFormOpen(null)}
                scope="draft"
                mode={formOpen?.mode ?? 'create'}
                transaction={formOpen?.mode === 'edit' ? formOpen.row : undefined}
                endpoint={formOpen?.mode === 'edit' ? `/draft_transactions/${formOpen.row.id}` : '/draft_transactions'}
                categories={options.categories}
                wallets={options.wallets}
                monthYears={options.month_years}
                title={formOpen?.mode === 'edit' ? 'Edit draft' : 'New draft'}
            />

            <ConfirmModal
                show={!!deleteTarget}
                title="Delete draft?"
                message={deleteTarget ? `Delete "${deleteTarget.name}"?` : ''}
                onClose={() => setDeleteTarget(null)}
                onConfirm={() => {
                    if (!deleteTarget) return;
                    router.delete(`/draft_transactions/${deleteTarget.id}`, {
                        preserveScroll: true,
                        onFinish: () => setDeleteTarget(null),
                    });
                }}
            />

            <TransactionFormModal
                show={!!promoteTarget}
                onClose={() => setPromoteTarget(null)}
                scope="normal"
                mode="edit"
                transaction={promoteTarget ?? undefined}
                endpoint="/draft_transactions/transfer_to_normal"
                method="post"
                categories={options.categories}
                wallets={options.wallets}
                monthYears={options.month_years}
                title="Promote to normal"
                extraData={promoteTarget ? { id: promoteTarget.id } : undefined}
            />
        </AuthenticatedLayout>
    );
}

// ───── Row ──────────────────────────────────────────────────────────
function DraftRow({
    row,
    onEdit,
    onDelete,
    onPromote,
}: {
    row: Row;
    onEdit: () => void;
    onDelete: () => void;
    onPromote: () => void;
}) {
    const { isExpanded, toggle } = useExpansion();
    const open = isExpanded(row.id);

    const qty = Number(row.quantity);
    const price = Number(row.price);
    const total = qty * price;
    const isCredit = row.direction === 'credit';

    const monthLabel = row.month_year
        ? `${row.month_year.year}-${String(row.month_year.month).padStart(2, '0')}`
        : null;

    return (
        <div className="group rounded-xl border border-border bg-card shadow-sm transition-colors hover:border-border/80">
            <div className="flex items-center gap-3 px-4 py-3">
                <button
                    type="button"
                    onClick={() => toggle(row.id)}
                    className="text-muted-foreground transition-colors hover:text-foreground"
                    title={open ? 'Collapse' : 'Expand'}
                >
                    {open ? <ChevronDownIcon className="h-4 w-4" /> : <ChevronRightIcon className="h-4 w-4" />}
                </button>

                <span
                    className={clsx(
                        'h-2 w-2 flex-shrink-0 rounded-full',
                        isCredit ? 'bg-success' : 'bg-destructive',
                    )}
                    title={isCredit ? 'Credit' : 'Debit'}
                />

                <div className="min-w-0 flex-1">
                    <div className="truncate text-sm font-medium text-foreground">{row.name}</div>
                    <div className="mt-0.5 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-muted-foreground">
                        <Meta icon={TagIcon} label={row.category?.name ?? '—'} />
                        <Meta icon={WalletIcon} label={row.wallet?.name ?? '—'} />
                        <Meta icon={CalendarIcon} label={row.date ?? '—'} />
                    </div>
                </div>

                <span
                    className={clsx(
                        'font-semibold tabular-nums',
                        isCredit ? 'text-success' : 'text-destructive',
                    )}
                >
                    {isCredit ? '+' : '-'}E£ {fmt(total)}
                </span>

                <div className="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100 pointer-coarse:opacity-100">
                    <IconButton onClick={onPromote} title="Promote to normal" tone="success">
                        <CheckCircleIcon className="h-4 w-4" />
                    </IconButton>
                    <IconButton onClick={onEdit} title="Edit">
                        <PencilSquareIcon className="h-4 w-4" />
                    </IconButton>
                    <IconButton onClick={onDelete} title="Delete" danger>
                        <TrashIcon className="h-4 w-4" />
                    </IconButton>
                </div>
            </div>

            <Transition
                show={open}
                enter="transition-all duration-200 ease-out overflow-hidden"
                enterFrom="max-h-0 opacity-0"
                enterTo="max-h-96 opacity-100"
                leave="transition-all duration-150 ease-in overflow-hidden"
                leaveFrom="max-h-96 opacity-100"
                leaveTo="max-h-0 opacity-0"
            >
                <div className="grid grid-cols-1 gap-3 border-t border-border bg-muted/30 px-4 py-3 text-sm sm:grid-cols-2">
                    <div className="space-y-1">
                        <DetailRow label="Quantity">
                            <span className="font-mono">{qty}</span>
                        </DetailRow>
                        <DetailRow label="Unit price">
                            <span className="font-mono tabular-nums">E£ {fmt(price)}</span>
                        </DetailRow>
                        <DetailRow label="Total">
                            <span className={clsx('font-mono tabular-nums font-semibold', isCredit ? 'text-success' : 'text-destructive')}>
                                {isCredit ? '+' : '-'}E£ {fmt(total)}
                            </span>
                        </DetailRow>
                    </div>
                    <div className="space-y-1">
                        {row.user && (
                            <DetailRow label="Created by">
                                <span className="inline-flex items-center gap-1">
                                    <UserIcon className="h-3.5 w-3.5" />
                                    {row.user.first_name} {row.user.last_name}
                                </span>
                            </DetailRow>
                        )}
                        {monthLabel && (
                            <DetailRow label="Month">
                                <span className="font-mono">{monthLabel}</span>
                            </DetailRow>
                        )}
                        <DetailRow label="Direction">
                            <span
                                className={clsx(
                                    'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium',
                                    isCredit ? 'bg-success/15 text-success' : 'bg-destructive/15 text-destructive',
                                )}
                            >
                                {isCredit ? 'Credit (income)' : 'Debit (expense)'}
                            </span>
                        </DetailRow>
                    </div>
                    {row.comment && (
                        <div className="sm:col-span-2">
                            <div className="text-xs font-medium uppercase tracking-wider text-muted-foreground">Comment</div>
                            <div className="mt-1 whitespace-pre-wrap rounded-md border border-border bg-card px-3 py-2 text-sm text-foreground">
                                {row.comment}
                            </div>
                        </div>
                    )}
                </div>
            </Transition>
        </div>
    );
}

function DetailRow({ label, children }: PropsWithChildren<{ label: string }>) {
    return (
        <div className="flex items-baseline justify-between gap-3">
            <span className="text-xs text-muted-foreground">{label}</span>
            <span className="text-sm text-foreground">{children}</span>
        </div>
    );
}

function Meta({ icon: Icon, label }: { icon: typeof TagIcon; label: string }) {
    return (
        <span className="inline-flex items-center gap-1 truncate">
            <Icon className="h-3 w-3 flex-shrink-0" />
            <span className="truncate">{label}</span>
        </span>
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
    tone?: 'success' | 'warning';
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            title={title}
            className={clsx(
                'rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted',
                danger && 'hover:text-destructive',
                tone === 'success' && 'hover:text-success',
                tone === 'warning' && 'hover:text-warning',
                !danger && !tone && 'hover:text-primary',
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
                    <InboxIcon className="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 className="text-base font-semibold text-foreground">{title}</h3>
                <p className="text-sm text-muted-foreground">{description}</p>
                {cta}
            </div>
        </div>
    );
}
