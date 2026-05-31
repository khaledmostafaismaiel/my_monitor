import {
    createContext,
    FormEventHandler,
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
    FolderIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronDoubleDownIcon,
    ChevronDoubleUpIcon,
    MagnifyingGlassIcon,
    XMarkIcon,
    EyeIcon,
    EyeSlashIcon,
    CheckCircleIcon,
    NoSymbolIcon,
    Bars3BottomLeftIcon,
} from '@heroicons/react/24/outline';
import clsx from 'clsx';
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Switch,
    Transition,
} from '@headlessui/react';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PageHeader from '@/Components/PageHeader';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import Select from '@/Components/Select';
import FormField from '@/Components/FormField';
import Modal from '@/Components/Modal';
import ConfirmModal from '@/Components/ConfirmModal';
import { toast } from '@/Components/FlashBanner';
import { Category } from '@/types';

type CategoryRow = Omit<Category, 'children'> & {
    status?: 'active' | 'inactive';
    limit?: number | null;
};

type CategoryNode = CategoryRow & { children: CategoryNode[] };

type Props = {
    categories: CategoryRow[];
};

const EXPANSION_KEY = 'categories.expanded';
const HIDE_INACTIVE_KEY = 'categories.hide_inactive';

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

// ───── Optimistic status overrides ──────────────────────────────────
type StatusApi = {
    statusOf: (cat: CategoryRow) => 'active' | 'inactive';
    setStatus: (cat: CategoryRow, status: 'active' | 'inactive') => void;
};

const StatusContext = createContext<StatusApi | null>(null);

function StatusProvider({ children }: PropsWithChildren) {
    const [overrides, setOverrides] = useState<Map<number, 'active' | 'inactive'>>(new Map());

    const api: StatusApi = useMemo(() => ({
        statusOf: (c) => overrides.get(c.id) ?? (c.status ?? 'active'),
        setStatus: (c, next) => {
            const current = overrides.get(c.id) ?? c.status ?? 'active';
            if (current === next) return;
            setOverrides((prev) => new Map(prev).set(c.id, next));
            axios
                .put(`/categories/${c.id}`, {
                    name: c.name,
                    status: next,
                    limit: c.limit ?? null,
                    parent_id: c.parent_id ?? null,
                })
                .then(() => router.reload({ only: ['categories'] }))
                .catch(() => {
                    setOverrides((prev) => {
                        const m = new Map(prev);
                        m.delete(c.id);
                        return m;
                    });
                    toast('Could not update status. Please try again.', 'error');
                });
        },
    }), [overrides]);

    return <StatusContext.Provider value={api}>{children}</StatusContext.Provider>;
}

function useStatus() {
    const ctx = useContext(StatusContext);
    if (!ctx) throw new Error('useStatus outside provider');
    return ctx;
}

// ───── Tree helpers ─────────────────────────────────────────────────
function buildTree(flat: CategoryRow[]): CategoryNode[] {
    const map = new Map<number, CategoryNode>();
    flat.forEach((c) => map.set(c.id, { ...c, children: [] }));
    const roots: CategoryNode[] = [];
    map.forEach((node) => {
        if (node.parent_id && map.has(node.parent_id)) {
            map.get(node.parent_id)!.children.push(node);
        } else {
            roots.push(node);
        }
    });
    // Surface "Main" first if present
    const mainIdx = roots.findIndex((r) => r.name === 'Main');
    if (mainIdx > 0) {
        const [main] = roots.splice(mainIdx, 1);
        roots.unshift(main);
    }
    return roots;
}

function filterTreeKeepingAncestors(roots: CategoryNode[], query: string, hideInactive: boolean, statusOf: (c: CategoryRow) => 'active' | 'inactive'): CategoryNode[] {
    const q = query.trim().toLowerCase();
    const matchesQuery = (n: CategoryNode) => !q || n.name.toLowerCase().includes(q);
    const matchesActive = (n: CategoryNode) => !hideInactive || statusOf(n) === 'active';

    const walk = (n: CategoryNode): CategoryNode | null => {
        const kept: CategoryNode[] = [];
        for (const c of n.children) {
            const r = walk(c);
            if (r) kept.push(r);
        }
        const selfOk = matchesQuery(n) && matchesActive(n);
        if (kept.length > 0 || selfOk) return { ...n, children: kept };
        return null;
    };

    return roots.map(walk).filter((r): r is CategoryNode => r !== null);
}

function walkIds(nodes: CategoryNode[]): number[] {
    const out: number[] = [];
    const visit = (n: CategoryNode) => {
        out.push(n.id);
        n.children.forEach(visit);
    };
    nodes.forEach(visit);
    return out;
}

function countDescendants(node: CategoryNode): number {
    let total = 0;
    const visit = (n: CategoryNode) => {
        total += n.children.length;
        n.children.forEach(visit);
    };
    visit(node);
    return total;
}

function useDebouncedValue<T>(value: T, delay: number): T {
    const [debounced, setDebounced] = useState(value);
    useEffect(() => {
        const t = setTimeout(() => setDebounced(value), delay);
        return () => clearTimeout(t);
    }, [value, delay]);
    return debounced;
}

// ───── Page ─────────────────────────────────────────────────────────
export default function CategoriesIndex(props: Props) {
    return (
        <ExpansionProvider>
            <StatusProvider>
                <CategoriesPage {...props} />
            </StatusProvider>
        </ExpansionProvider>
    );
}

function CategoriesPage({ categories }: Props) {
    const expansion = useExpansion();
    const { statusOf } = useStatus();

    const [filtersOpen, setFiltersOpen] = useState(false);
    const [formOpen, setFormOpen] = useState<
        | { mode: 'create'; parent?: CategoryRow; initialName?: string }
        | { mode: 'edit'; category: CategoryRow }
        | null
    >(null);
    const [deleteTarget, setDeleteTarget] = useState<CategoryRow | null>(null);

    const [search, setSearch] = useState('');
    const [hideInactive, setHideInactive] = useState<boolean>(
        () => typeof window !== 'undefined' && window.localStorage.getItem(HIDE_INACTIVE_KEY) === '1',
    );

    const debouncedSearch = useDebouncedValue(search, 200);

    const tree = useMemo(() => buildTree(categories), [categories]);
    const visibleTree = useMemo(
        () => filterTreeKeepingAncestors(tree, debouncedSearch, hideInactive, statusOf),
        [tree, debouncedSearch, hideInactive, statusOf],
    );

    const hasActiveFilters = !!debouncedSearch || hideInactive;
    const searchRef = useRef<HTMLInputElement>(null);

    const toggleHideInactive = (v: boolean) => {
        setHideInactive(v);
        try {
            window.localStorage.setItem(HIDE_INACTIVE_KEY, v ? '1' : '0');
        } catch {
            /* ignore */
        }
    };

    // Auto-focus search when filter bar opens
    useEffect(() => {
        if (filtersOpen) {
            const t = setTimeout(() => searchRef.current?.focus(), 50);
            return () => clearTimeout(t);
        }
    }, [filtersOpen]);

    // Auto-expand matching branches while searching
    useEffect(() => {
        if (debouncedSearch) {
            const ids = walkIds(visibleTree);
            expansion.setMany(ids, true);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [debouncedSearch]);

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

    const expandAll = () => expansion.setMany(walkIds(tree), true);
    const collapseAll = () => expansion.clear();

    return (
        <AuthenticatedLayout>
            <Head title="Categories" />

            <PageHeader
                title="Categories"
                description="Organize transactions by purpose"
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
                            <PlusIcon className="mr-1.5 h-4 w-4" /> Add Category
                        </PrimaryButton>
                    </>
                }
            />

            {hasActiveFilters && (
                <div className="mb-4 flex flex-wrap items-center gap-2">
                    {debouncedSearch && (
                        <FilterChip
                            label={`Name: ${debouncedSearch}`}
                            onClear={() => setSearch('')}
                        />
                    )}
                    {hideInactive && (
                        <FilterChip
                            label="Hiding inactive"
                            onClear={() => toggleHideInactive(false)}
                        />
                    )}
                    <button
                        type="button"
                        onClick={() => {
                            setSearch('');
                            toggleHideInactive(false);
                        }}
                        className="text-xs text-muted-foreground transition-colors hover:text-foreground"
                    >
                        Clear all
                    </button>
                </div>
            )}

            {filtersOpen && (
                <div className="mb-4 rounded-xl border border-border bg-card p-4">
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div className="sm:col-span-2">
                            <FormField label="Search by name">
                                <TextInput
                                    ref={searchRef as unknown as React.Ref<HTMLInputElement>}
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Type to search…"
                                />
                            </FormField>
                        </div>
                        <div className="flex items-end gap-2">
                            {hideInactive ? <EyeSlashIcon className="h-4 w-4 text-muted-foreground" /> : <EyeIcon className="h-4 w-4 text-muted-foreground" />}
                            <span className="text-sm text-muted-foreground">Hide inactive</span>
                            <Switch
                                checked={hideInactive}
                                onChange={toggleHideInactive}
                                className={clsx(
                                    'relative inline-flex h-5 w-9 items-center rounded-full transition-colors',
                                    hideInactive ? 'bg-primary' : 'bg-muted',
                                )}
                            >
                                <span
                                    className={clsx(
                                        'inline-block h-3.5 w-3.5 transform rounded-full bg-card transition-transform',
                                        hideInactive ? 'translate-x-5' : 'translate-x-1',
                                    )}
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
            )}

            <QuickAdd onOpenFull={(name) => setFormOpen({ mode: 'create', initialName: name })} />

            <div className="space-y-2">
                {visibleTree.length === 0 ? (
                    hasActiveFilters ? (
                        <EmptyState
                            title="No categories match"
                            description="Adjust the search or unhide inactive categories."
                            cta={
                                <SecondaryButton
                                    onClick={() => {
                                        setSearch('');
                                        toggleHideInactive(false);
                                    }}
                                >
                                    Clear filters
                                </SecondaryButton>
                            }
                        />
                    ) : (
                        <EmptyState
                            title="No categories yet"
                            description="Quick-add one above, or open the full form for status & limit."
                            cta={
                                <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                                    <PlusIcon className="mr-1.5 h-4 w-4" /> Add your first category
                                </PrimaryButton>
                            }
                        />
                    )
                ) : (
                    visibleTree.map((cat) => (
                        <CategoryRowItem
                            key={cat.id}
                            node={cat}
                            onEdit={(c) => setFormOpen({ mode: 'edit', category: c })}
                            onAddChild={(parent) => setFormOpen({ mode: 'create', parent })}
                            onDelete={(c) => setDeleteTarget(c)}
                        />
                    ))
                )}
            </div>

            <CategoryFormModal
                show={!!formOpen}
                onClose={() => setFormOpen(null)}
                mode={formOpen?.mode ?? 'create'}
                category={formOpen?.mode === 'edit' ? formOpen.category : undefined}
                parent={formOpen?.mode === 'create' ? formOpen.parent : undefined}
                initialName={formOpen?.mode === 'create' ? formOpen.initialName : undefined}
                allCategories={categories}
            />

            <ConfirmModal
                show={!!deleteTarget}
                title="Delete category?"
                message={deleteTarget ? `Delete "${deleteTarget.name}"? Categories with subcategories or transactions can't be deleted — reassign or remove those first.` : ''}
                onClose={() => setDeleteTarget(null)}
                onConfirm={() => {
                    if (!deleteTarget) return;
                    router.delete(`/categories/${deleteTarget.id}`, {
                        preserveScroll: true,
                        onFinish: () => setDeleteTarget(null),
                    });
                }}
            />
        </AuthenticatedLayout>
    );
}

// ───── Quick-add ────────────────────────────────────────────────────
function QuickAdd({ onOpenFull }: { onOpenFull: (name: string) => void }) {
    const [value, setValue] = useState('');
    const [submitting, setSubmitting] = useState(false);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        const name = value.trim();
        if (!name) return;
        setSubmitting(true);
        axios
            .post('/categories', { name, status: 'active' })
            .then(() => {
                setValue('');
                router.reload({ only: ['categories'] });
            })
            .catch(() => toast('Could not create category.', 'error'))
            .finally(() => setSubmitting(false));
    };

    const handleKey = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Enter' && e.shiftKey) {
            e.preventDefault();
            onOpenFull(value);
        }
    };

    return (
        <form
            onSubmit={submit}
            className="mb-3 flex items-center gap-2 rounded-xl border border-border bg-card px-3 py-2 shadow-sm"
        >
            <PlusIcon className="h-4 w-4 text-muted-foreground" />
            <input
                type="text"
                value={value}
                disabled={submitting}
                onChange={(e) => setValue(e.target.value)}
                onKeyDown={handleKey}
                placeholder="Quick add a category… (Enter to save · Shift+Enter for full form)"
                className="block flex-1 border-0 bg-transparent p-1 text-sm text-foreground placeholder:text-muted-foreground focus:ring-0"
            />
            {value.trim() && (
                <button
                    type="button"
                    onClick={() => onOpenFull(value)}
                    className="rounded p-1 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    title="Open full form"
                >
                    <PencilSquareIcon className="h-4 w-4" />
                </button>
            )}
        </form>
    );
}

// ───── Row ──────────────────────────────────────────────────────────
function CategoryRowItem({
    node,
    depth = 0,
    onEdit,
    onAddChild,
    onDelete,
}: {
    node: CategoryNode;
    depth?: number;
    onEdit: (c: CategoryRow) => void;
    onAddChild: (parent: CategoryRow) => void;
    onDelete: (c: CategoryRow) => void;
}) {
    const { isExpanded, toggle } = useExpansion();
    const { statusOf, setStatus } = useStatus();
    const open = isExpanded(node.id);

    const hasChildren = node.children.length > 0;
    const status = statusOf(node);
    const isInactive = status === 'inactive';
    const descCount = useMemo(() => countDescendants(node), [node]);

    const limit = node.limit != null ? Number(node.limit) : 0;

    return (
        <div
            className={clsx(
                'group rounded-xl border border-border bg-card shadow-sm transition-colors hover:border-border/80',
                isInactive && 'opacity-70',
            )}
            style={{ marginLeft: depth * 16 }}
        >
            <div className="flex items-center gap-2 px-4 py-3">
                {hasChildren ? (
                    <button
                        type="button"
                        onClick={() => toggle(node.id)}
                        className="text-muted-foreground transition-colors hover:text-foreground"
                        title={open ? 'Collapse' : 'Expand'}
                    >
                        {open ? <ChevronDownIcon className="h-4 w-4" /> : <ChevronRightIcon className="h-4 w-4" />}
                    </button>
                ) : (
                    <span className="w-4" />
                )}

                <FolderIcon className={clsx('h-4 w-4', isInactive ? 'text-muted-foreground' : 'text-primary')} />

                <span
                    className={clsx(
                        'truncate font-semibold',
                        isInactive ? 'text-muted-foreground line-through' : 'text-foreground',
                    )}
                >
                    {node.name}
                </span>

                {!open && hasChildren && (
                    <span className="hidden rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground sm:inline-block">
                        {descCount} {descCount === 1 ? 'subcategory' : 'subcategories'}
                    </span>
                )}

                {limit > 0 && (
                    <span className="hidden items-center gap-1 rounded-md bg-primary/10 px-1.5 py-0.5 text-xs font-medium text-primary sm:inline-flex">
                        <Bars3BottomLeftIcon className="h-3 w-3" />
                        E£ {limit.toLocaleString()}
                    </span>
                )}

                <div className="ml-auto flex items-center gap-1">
                    <StatusToggle status={status} onChange={(next) => setStatus(node, next)} />
                    <div className="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100 pointer-coarse:opacity-100">
                        <IconButton onClick={() => onAddChild(node)} title="Add subcategory">
                            <PlusIcon className="h-4 w-4" />
                        </IconButton>
                        <IconButton onClick={() => onEdit(node)} title="Edit">
                            <PencilSquareIcon className="h-4 w-4" />
                        </IconButton>
                        <IconButton onClick={() => onDelete(node)} title="Delete" danger>
                            <TrashIcon className="h-4 w-4" />
                        </IconButton>
                    </div>
                </div>
            </div>

            <Transition
                show={open && hasChildren}
                enter="transition-all duration-200 ease-out overflow-hidden"
                enterFrom="max-h-0 opacity-0"
                enterTo="max-h-[4000px] opacity-100"
                leave="transition-all duration-150 ease-in overflow-hidden"
                leaveFrom="max-h-[4000px] opacity-100"
                leaveTo="max-h-0 opacity-0"
            >
                <div className="space-y-2 border-t border-border px-4 pb-3 pt-2">
                    {node.children.map((child) => (
                        <CategoryRowItem
                            key={child.id}
                            node={child}
                            depth={depth + 1}
                            onEdit={onEdit}
                            onAddChild={onAddChild}
                            onDelete={onDelete}
                        />
                    ))}
                </div>
            </Transition>
        </div>
    );
}

// ───── Status toggle (single click flip) ────────────────────────────
function StatusToggle({ status, onChange }: { status: 'active' | 'inactive'; onChange: (s: 'active' | 'inactive') => void }) {
    const Icon = status === 'active' ? CheckCircleIcon : NoSymbolIcon;
    const next: 'active' | 'inactive' = status === 'active' ? 'inactive' : 'active';
    return (
        <button
            type="button"
            onClick={() => onChange(next)}
            title={status === 'active' ? 'Active — click to deactivate' : 'Inactive — click to activate'}
            className={clsx(
                'rounded-md p-1.5 transition-colors hover:bg-muted',
                status === 'active' ? 'text-success' : 'text-muted-foreground',
            )}
        >
            <Icon className="h-5 w-5" />
        </button>
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
}: {
    children: React.ReactNode;
    onClick: () => void;
    title: string;
    danger?: boolean;
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            title={title}
            className={clsx(
                'rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted',
                danger ? 'hover:text-destructive' : 'hover:text-primary',
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
                    <FolderIcon className="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 className="text-base font-semibold text-foreground">{title}</h3>
                <p className="text-sm text-muted-foreground">{description}</p>
                {cta}
            </div>
        </div>
    );
}

// ───── Form modal ───────────────────────────────────────────────────
function CategoryFormModal({
    show,
    onClose,
    mode,
    category,
    parent,
    initialName,
    allCategories,
}: {
    show: boolean;
    onClose: () => void;
    mode: 'create' | 'edit';
    category?: CategoryRow;
    parent?: CategoryRow;
    initialName?: string;
    allCategories: CategoryRow[];
}) {
    const form = useForm({
        name: category?.name ?? initialName ?? '',
        status: (category?.status ?? 'active') as 'active' | 'inactive',
        limit: category?.limit?.toString() ?? '',
        parent_id: (category?.parent_id ?? parent?.id ?? '')?.toString() ?? '',
    });

    useEffect(() => {
        if (show) {
            form.setData({
                name: category?.name ?? initialName ?? '',
                status: (category?.status ?? 'active') as 'active' | 'inactive',
                limit: category?.limit?.toString() ?? '',
                parent_id: (category?.parent_id ?? parent?.id ?? '')?.toString() ?? '',
            });
            form.clearErrors();
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [show, category?.id, parent?.id, initialName]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const opts = { onSuccess: () => onClose(), preserveScroll: true };
        if (mode === 'edit' && category) form.put(`/categories/${category.id}`, opts);
        else form.post('/categories', opts);
    };

    return (
        <Modal show={show} onClose={onClose} maxWidth="md">
            <form
                onSubmit={submit}
                onKeyDown={(e) => {
                    if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
                        e.preventDefault();
                        submit(e as unknown as React.FormEvent);
                    }
                }}
                className="p-6"
            >
                <h3 className="text-lg font-semibold text-foreground">
                    {mode === 'edit' ? 'Edit category' : parent ? `Add subcategory of "${parent.name}"` : 'New category'}
                </h3>

                <div className="mt-4 space-y-4">
                    <FormField label="Name" htmlFor="cat_name" error={form.errors.name}>
                        <TextInput
                            id="cat_name"
                            value={form.data.name}
                            onChange={(e) => form.setData('name', e.target.value)}
                            required
                            isFocused
                        />
                    </FormField>

                    <FormField label="Status" htmlFor="cat_status" error={form.errors.status}>
                        <Select
                            id="cat_status"
                            value={form.data.status}
                            onChange={(e) => form.setData('status', e.target.value as 'active' | 'inactive')}
                            options={[
                                { value: 'active', label: 'Active' },
                                { value: 'inactive', label: 'Inactive' },
                            ]}
                            required
                        />
                    </FormField>

                    <FormField
                        label="Monthly limit (optional)"
                        htmlFor="cat_limit"
                        error={form.errors.limit}
                        hint="Used to track budgets per category."
                    >
                        <TextInput
                            id="cat_limit"
                            type="number"
                            min="0"
                            step="0.01"
                            value={form.data.limit}
                            onChange={(e) => form.setData('limit', e.target.value)}
                        />
                    </FormField>

                    <FormField label="Parent (optional)" error={form.errors.parent_id}>
                        <ParentCombobox
                            value={form.data.parent_id}
                            onChange={(v) => form.setData('parent_id', v)}
                            options={allCategories.filter((c) => !category || c.id !== category.id)}
                        />
                    </FormField>
                </div>

                <div className="mt-6 flex items-center justify-between gap-3">
                    <span className="text-xs text-muted-foreground">Tip: press ⌘/Ctrl+Enter to save</span>
                    <div className="flex gap-3">
                        <SecondaryButton type="button" onClick={onClose} disabled={form.processing}>
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton disabled={form.processing}>
                            {form.processing ? 'Saving…' : 'Save'}
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Modal>
    );
}

// ───── Combobox for parent select ───────────────────────────────────
function ParentCombobox({
    value,
    onChange,
    options,
}: {
    value: string;
    onChange: (v: string) => void;
    options: CategoryRow[];
}) {
    const [query, setQuery] = useState('');
    const selected = options.find((o) => String(o.id) === value) ?? null;

    const filtered = query
        ? options.filter((o) => o.name.toLowerCase().includes(query.toLowerCase()))
        : options;

    return (
        <Combobox<CategoryRow | null>
            value={selected}
            onChange={(v) => onChange(v ? String(v.id) : '')}
            nullable
        >
            <div className="relative">
                <ComboboxInput
                    onChange={(e) => setQuery(e.target.value)}
                    displayValue={(v: CategoryRow | null) => v?.name ?? ''}
                    placeholder="No parent"
                    className="block w-full rounded-md border-input bg-card pr-9 text-foreground shadow-sm placeholder:text-muted-foreground focus:border-ring focus:ring-ring"
                />
                <ComboboxButton className="absolute inset-y-0 right-0 flex items-center pr-2 text-muted-foreground">
                    <ChevronDownIcon className="h-4 w-4" />
                </ComboboxButton>
                {value && (
                    <button
                        type="button"
                        onClick={() => {
                            setQuery('');
                            onChange('');
                        }}
                        className="absolute inset-y-0 right-7 flex items-center text-muted-foreground hover:text-destructive"
                        title="Clear parent"
                    >
                        <XMarkIcon className="h-3.5 w-3.5" />
                    </button>
                )}

                <ComboboxOptions className="absolute z-30 mt-1 max-h-60 w-full overflow-auto rounded-md border border-border bg-popover py-1 text-sm shadow-lg ring-1 ring-foreground/5 focus:outline-none">
                    {filtered.length === 0 ? (
                        <div className="px-3 py-2 text-muted-foreground">No matches.</div>
                    ) : (
                        filtered.map((o) => (
                            <ComboboxOption
                                key={o.id}
                                value={o}
                                className={({ focus }) =>
                                    clsx('flex cursor-pointer items-center gap-2 px-3 py-1.5', focus && 'bg-muted')
                                }
                            >
                                {({ selected: sel }) => (
                                    <>
                                        <span className={clsx('flex-1 truncate text-popover-foreground', sel && 'font-medium')}>
                                            {o.name}
                                        </span>
                                        {sel && <CheckCircleIcon className="h-4 w-4 text-primary" />}
                                    </>
                                )}
                            </ComboboxOption>
                        ))
                    )}
                </ComboboxOptions>
            </div>
        </Combobox>
    );
}
