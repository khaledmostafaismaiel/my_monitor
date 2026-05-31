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
import { Head, Link, router, useForm } from '@inertiajs/react';
import {
    ArrowLeftIcon,
    ArrowRightIcon,
    CalendarDaysIcon,
    FolderIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronDoubleDownIcon,
    ChevronDoubleUpIcon,
    MagnifyingGlassIcon,
    XMarkIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon,
    ScaleIcon,
    EyeIcon,
    EyeSlashIcon,
    WalletIcon,
    ChatBubbleLeftEllipsisIcon,
    PencilSquareIcon,
    TrashIcon,
    ArrowUturnLeftIcon,
} from '@heroicons/react/24/outline';
import clsx from 'clsx';
import { Transition } from '@headlessui/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PageHeader from '@/Components/PageHeader';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import FormField from '@/Components/FormField';
import Modal from '@/Components/Modal';
import ConfirmModal from '@/Components/ConfirmModal';
import TransactionFormModal from '@/Components/TransactionFormModal';
import { toast } from '@/Components/FlashBanner';
import { Category, MonthYear, Wallet } from '@/types';

type CategoryNode = Omit<Category, 'children'> & {
    children?: CategoryNode[];
    total_spent?: string | number;
    limit?: number | null;
    normal_transactions?: TxRow[];
};

type TxRow = {
    id: number;
    name: string;
    price: number | string;
    quantity: number | string;
    direction: string;
    date?: string;
    comment?: string | null;
    category_id?: number;
    wallet_id?: number | null;
    month_year_id?: number | null;
    wallet?: { id: number; name: string } | null;
};

type Options = {
    categories: { id: number; name: string }[];
    wallets: Pick<Wallet, 'id' | 'name'>[];
    month_years: Pick<MonthYear, 'id' | 'month' | 'year'>[];
};

type Props = {
    monthYear: MonthYear;
    prev?: MonthYear | null;
    next?: MonthYear | null;
    rootCategories: CategoryNode[];
    options: Options;
};

// ───── Row actions context (avoids prop-drilling through recursive tree) ─────
type RowActions = {
    onEdit: (tx: TxRow) => void;
    onDelete: (tx: TxRow) => void;
    onMoveToDraft: (tx: TxRow) => void;
};
const RowActionsContext = createContext<RowActions | null>(null);
const useRowActions = () => useContext(RowActionsContext);

const EXPANSION_KEY = 'month_year.expanded';
const PRIVACY_KEY = 'month_year.numbers_visible';

const fmt = (v: string | number | undefined) => {
    const n = typeof v === 'string' ? parseFloat(v) : v ?? 0;
    return new Intl.NumberFormat('en-EG', { minimumFractionDigits: 2 }).format(n || 0);
};

const monthLabel = (m: { year: number; month: number }) =>
    `${m.year}-${String(m.month).padStart(2, '0')}`;

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

// ───── Privacy ──────────────────────────────────────────────────────
const PrivacyContext = createContext<boolean>(false);
const usePrivacyHidden = () => useContext(PrivacyContext);

function Sensitive({ children }: PropsWithChildren) {
    const hidden = usePrivacyHidden();
    return (
        <span
            aria-hidden={hidden ? 'true' : undefined}
            className={clsx('inline-block transition-[filter] duration-150', hidden && 'select-none blur-md')}
        >
            {children}
        </span>
    );
}

// ───── Tree helpers ─────────────────────────────────────────────────
function walkIds(nodes: CategoryNode[]): number[] {
    const out: number[] = [];
    const visit = (n: CategoryNode) => {
        out.push(n.id);
        (n.children ?? []).forEach(visit);
    };
    nodes.forEach(visit);
    return out;
}

function collectTransactions(nodes: CategoryNode[]): TxRow[] {
    const out: TxRow[] = [];
    const visit = (n: CategoryNode) => {
        for (const tx of n.normal_transactions ?? []) out.push(tx);
        (n.children ?? []).forEach(visit);
    };
    nodes.forEach(visit);
    return out;
}

function countTransactionsInSubtree(node: CategoryNode): number {
    let count = (node.normal_transactions ?? []).length;
    for (const c of node.children ?? []) count += countTransactionsInSubtree(c);
    return count;
}

function filterTreeKeepingAncestors(roots: CategoryNode[], q: string): CategoryNode[] {
    const query = q.trim().toLowerCase();
    if (!query) return roots;
    const matches = (n: CategoryNode) => n.name.toLowerCase().includes(query);
    const walk = (n: CategoryNode): CategoryNode | null => {
        const kept: CategoryNode[] = [];
        for (const c of n.children ?? []) {
            const r = walk(c);
            if (r) kept.push(r);
        }
        if (matches(n) || kept.length > 0) return { ...n, children: kept };
        return null;
    };
    return roots.map(walk).filter((r): r is CategoryNode => r !== null);
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
export default function MonthYearShow(props: Props) {
    return (
        <ExpansionProvider>
            <MonthYearPage {...props} />
        </ExpansionProvider>
    );
}

function MonthYearPage({ monthYear, prev, next, rootCategories, options }: Props) {
    const expansion = useExpansion();

    const [filtersOpen, setFiltersOpen] = useState(false);
    const [search, setSearch] = useState('');
    const debouncedSearch = useDebouncedValue(search, 200);

    // Per-transaction actions
    const [formOpen, setFormOpen] = useState<{ row: TxRow } | null>(null);
    const [deleteTarget, setDeleteTarget] = useState<TxRow | null>(null);
    const [moveTarget, setMoveTarget] = useState<TxRow | null>(null);

    const rowActions: RowActions = useMemo(
        () => ({
            onEdit: (row) => setFormOpen({ row }),
            onDelete: (row) => setDeleteTarget(row),
            onMoveToDraft: (row) => setMoveTarget(row),
        }),
        [],
    );

    // Privacy default-hidden each load; toggle persists per-session
    const [numbersVisible, setNumbersVisible] = useState(false);
    useEffect(() => {
        try {
            if (window.sessionStorage.getItem(PRIVACY_KEY) === '1') setNumbersVisible(true);
        } catch {
            /* ignore */
        }
    }, []);
    const toggleNumbers = () => {
        setNumbersVisible((prev) => {
            const next = !prev;
            try {
                if (next) window.sessionStorage.setItem(PRIVACY_KEY, '1');
                else window.sessionStorage.removeItem(PRIVACY_KEY);
            } catch {
                /* ignore */
            }
            return next;
        });
    };

    const visibleTree = useMemo(
        () => filterTreeKeepingAncestors(rootCategories, debouncedSearch),
        [rootCategories, debouncedSearch],
    );

    // Page totals — walk the entire (unfiltered) tree
    const totals = useMemo(() => {
        let credit = 0;
        let debit = 0;
        for (const tx of collectTransactions(rootCategories)) {
            const amt = Number(tx.price) * Number(tx.quantity);
            if (tx.direction === 'credit') credit += amt;
            else debit += amt;
        }
        return { credit, debit, net: credit - debit };
    }, [rootCategories]);

    const searchRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (filtersOpen) {
            const t = setTimeout(() => searchRef.current?.focus(), 50);
            return () => clearTimeout(t);
        }
    }, [filtersOpen]);

    // Auto-expand search matches
    useEffect(() => {
        if (debouncedSearch) {
            expansion.setMany(walkIds(visibleTree), true);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [debouncedSearch]);

    // Keyboard: arrow nav between months, plus / for search
    useEffect(() => {
        const onKey = (e: KeyboardEvent) => {
            const target = e.target as HTMLElement | null;
            const tag = target?.tagName?.toLowerCase();
            if (tag === 'input' || tag === 'textarea' || target?.isContentEditable) return;
            if (e.metaKey || e.ctrlKey || e.altKey) return;

            if (e.key === 'ArrowLeft' && prev) {
                e.preventDefault();
                window.location.href = `/month_years/${prev.id}`;
            } else if (e.key === 'ArrowRight' && next) {
                e.preventDefault();
                window.location.href = `/month_years/${next.id}`;
            } else if (e.key === '/') {
                e.preventDefault();
                setFiltersOpen(true);
            }
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [prev?.id, next?.id]);

    const expandAll = () => expansion.setMany(walkIds(rootCategories), true);
    const collapseAll = () => expansion.clear();

    const label = monthLabel(monthYear);

    return (
        <PrivacyContext.Provider value={!numbersVisible}>
            <AuthenticatedLayout>
                <Head title={`Month ${label}`} />

                <div className="mx-auto max-w-5xl">
                    <PageHeader
                        title={`Month ${label}`}
                        description="Per-category breakdown of normal transactions"
                        actions={
                            <>
                                <SecondaryButton
                                    onClick={toggleNumbers}
                                    title={numbersVisible ? 'Hide amounts' : 'Show amounts'}
                                >
                                    {numbersVisible ? (
                                        <EyeSlashIcon className="mr-1.5 h-4 w-4" />
                                    ) : (
                                        <EyeIcon className="mr-1.5 h-4 w-4" />
                                    )}
                                    {numbersVisible ? 'Hide' : 'Show'}
                                </SecondaryButton>
                                <IconButton onClick={expandAll} title="Expand all">
                                    <ChevronDoubleDownIcon className="h-4 w-4" />
                                </IconButton>
                                <IconButton onClick={collapseAll} title="Collapse all">
                                    <ChevronDoubleUpIcon className="h-4 w-4" />
                                </IconButton>
                                <SecondaryButton onClick={() => setFiltersOpen((v) => !v)}>
                                    <MagnifyingGlassIcon className="mr-1.5 h-4 w-4" /> Search
                                </SecondaryButton>
                            </>
                        }
                    />

                    {/* Month navigation strip */}
                    <div className="mb-5 flex items-center justify-between gap-3 rounded-xl border border-border bg-card p-2 shadow-sm">
                        <MonthNavLink target={prev} direction="prev" />
                        <div className="text-center">
                            <div className="text-xs uppercase tracking-wider text-muted-foreground">Viewing</div>
                            <div className="font-heading text-lg font-semibold text-foreground">{label}</div>
                        </div>
                        <MonthNavLink target={next} direction="next" />
                    </div>

                    {/* Summary cards */}
                    <div className="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <SummaryCard
                            label="Income"
                            value={`+E£ ${fmt(totals.credit)}`}
                            icon={ArrowTrendingUpIcon}
                            tone="success"
                        />
                        <SummaryCard
                            label="Expenses"
                            value={`-E£ ${fmt(totals.debit)}`}
                            icon={ArrowTrendingDownIcon}
                            tone="destructive"
                        />
                        <SummaryCard
                            label="Net"
                            value={`${totals.net >= 0 ? '+' : '-'}E£ ${fmt(Math.abs(totals.net))}`}
                            icon={ScaleIcon}
                            tone={totals.net >= 0 ? 'success' : 'destructive'}
                            highlight
                        />
                    </div>

                    {filtersOpen && (
                        <div className="mb-4 rounded-xl border border-border bg-card p-4">
                            <FormField label="Search category">
                                <TextInput
                                    ref={searchRef as unknown as React.Ref<HTMLInputElement>}
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    placeholder="Type a category name…"
                                />
                            </FormField>
                        </div>
                    )}

                    {debouncedSearch && (
                        <div className="mb-3 flex items-center gap-2">
                            <span className="inline-flex items-center gap-1 rounded-full border border-border bg-card px-2.5 py-1 text-xs font-medium text-foreground">
                                Name: {debouncedSearch}
                                <button
                                    type="button"
                                    onClick={() => setSearch('')}
                                    className="rounded-full text-muted-foreground hover:text-destructive"
                                    title="Clear"
                                >
                                    <XMarkIcon className="h-3.5 w-3.5" />
                                </button>
                            </span>
                        </div>
                    )}

                    <RowActionsContext.Provider value={rowActions}>
                        <div className="space-y-2">
                            {visibleTree.length === 0 ? (
                                debouncedSearch ? (
                                    <EmptyCard
                                        icon={MagnifyingGlassIcon}
                                        title="No categories match"
                                        description="Try a different search term."
                                    />
                                ) : rootCategories.length === 0 ? (
                                    <EmptyCard
                                        icon={CalendarDaysIcon}
                                        title="No transactions yet"
                                        description="Add a normal transaction in this month to see the breakdown here."
                                    />
                                ) : null
                            ) : (
                                visibleTree.map((c) => <CategoryRow key={c.id} node={c} />)
                            )}
                        </div>
                    </RowActionsContext.Provider>
                </div>

                {/* Edit transaction */}
                <TransactionFormModal
                    show={!!formOpen}
                    onClose={() => setFormOpen(null)}
                    scope="normal"
                    mode="edit"
                    transaction={formOpen?.row as unknown as Partial<import('@/types').Transaction> | undefined}
                    endpoint={formOpen ? `/normal_transactions/${formOpen.row.id}` : ''}
                    categories={options.categories}
                    wallets={options.wallets}
                    monthYears={options.month_years}
                    title="Edit transaction"
                />

                {/* Delete transaction */}
                <ConfirmModal
                    show={!!deleteTarget}
                    title="Delete transaction?"
                    message={deleteTarget ? `Delete "${deleteTarget.name}"? This can't be undone.` : ''}
                    onClose={() => setDeleteTarget(null)}
                    onConfirm={() => {
                        if (!deleteTarget) return;
                        router.delete(`/normal_transactions/${deleteTarget.id}`, {
                            preserveScroll: true,
                            onFinish: () => setDeleteTarget(null),
                        });
                    }}
                />

                {/* Move to draft */}
                <MoveToDraftModal
                    show={!!moveTarget}
                    row={moveTarget}
                    onClose={() => setMoveTarget(null)}
                />
            </AuthenticatedLayout>
        </PrivacyContext.Provider>
    );
}

// ───── Move-to-draft modal ──────────────────────────────────────────
function MoveToDraftModal({
    show,
    row,
    onClose,
}: {
    show: boolean;
    row: TxRow | null;
    onClose: () => void;
}) {
    const form = useForm<{ id: number }>({ id: 0 });

    const submit = () => {
        if (!row) return;
        form.transform(() => ({ id: row.id }));
        form.post('/normal_transactions/transfer_to_draft', {
            onSuccess: () => onClose(),
            onError: () => toast('Could not move to drafts.', 'error'),
            preserveScroll: true,
        });
    };

    return (
        <Modal show={show} onClose={onClose} maxWidth="md">
            <div className="p-6">
                <div className="flex gap-4">
                    <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-warning/15 text-warning">
                        <ArrowUturnLeftIcon className="h-6 w-6" />
                    </div>
                    <div className="flex-1">
                        <h3 className="text-lg font-semibold text-foreground">Move to drafts?</h3>
                        <p className="mt-2 text-sm text-muted-foreground">
                            {row ? `"${row.name}" will be unfinalized and moved to your Drafts list.` : ''}
                        </p>
                    </div>
                </div>

                <div className="mt-6 flex justify-end gap-3">
                    <SecondaryButton type="button" onClick={onClose} disabled={form.processing}>
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton onClick={submit} disabled={form.processing}>
                        {form.processing ? 'Moving…' : 'Move to drafts'}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    );
}

// ───── Month nav link ───────────────────────────────────────────────
function MonthNavLink({
    target,
    direction,
}: {
    target?: MonthYear | null;
    direction: 'prev' | 'next';
}) {
    const ArrowIcon = direction === 'prev' ? ArrowLeftIcon : ArrowRightIcon;
    const hint = direction === 'prev' ? '← key' : '→ key';

    if (!target) {
        return (
            <span className="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-muted-foreground/70">
                {direction === 'prev' && <ArrowIcon className="h-4 w-4" />}
                <span>No {direction === 'prev' ? 'previous' : 'next'}</span>
                {direction === 'next' && <ArrowIcon className="h-4 w-4" />}
            </span>
        );
    }
    return (
        <Link
            href={`/month_years/${target.id}`}
            title={hint}
            className={clsx(
                'inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm font-medium text-foreground transition-colors',
                'hover:bg-muted hover:text-primary',
            )}
        >
            {direction === 'prev' && <ArrowIcon className="h-4 w-4" />}
            <span className="font-mono">{monthLabel(target)}</span>
            {direction === 'next' && <ArrowIcon className="h-4 w-4" />}
        </Link>
    );
}

// ───── Summary card ─────────────────────────────────────────────────
function SummaryCard({
    label,
    value,
    icon: Icon,
    tone,
    highlight,
}: {
    label: string;
    value: string;
    icon: typeof ScaleIcon;
    tone: 'success' | 'destructive';
    highlight?: boolean;
}) {
    const toneClasses = tone === 'success' ? 'bg-success/15 text-success' : 'bg-destructive/15 text-destructive';
    return (
        <div
            className={clsx(
                'rounded-xl border bg-card p-4 shadow-sm',
                highlight ? 'border-primary/30' : 'border-border',
            )}
        >
            <div className="flex items-start justify-between">
                <div>
                    <p className="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{label}</p>
                    <p className={clsx('mt-1 font-heading text-xl font-bold', tone === 'success' ? 'text-success' : 'text-destructive')}>
                        <Sensitive>{value}</Sensitive>
                    </p>
                </div>
                <div className={clsx('flex h-9 w-9 items-center justify-center rounded-full', toneClasses)}>
                    <Icon className="h-5 w-5" />
                </div>
            </div>
        </div>
    );
}

// ───── Category row ─────────────────────────────────────────────────
function CategoryRow({ node, depth = 0 }: { node: CategoryNode; depth?: number }) {
    const { isExpanded, toggle } = useExpansion();
    const open = isExpanded(node.id);

    const total = Number(node.total_spent ?? 0);
    const limit = node.limit ? Number(node.limit) : 0;
    const usedRatio = limit > 0 ? Math.min(Math.abs(total) / limit, 1.5) : 0;
    const usedPct = Math.round(usedRatio * 100);
    const overLimit = limit > 0 && Math.abs(total) > limit;

    const hasChildren = (node.children?.length ?? 0) > 0;
    const directTxCount = node.normal_transactions?.length ?? 0;
    const totalTxCount = useMemo(() => countTransactionsInSubtree(node), [node]);
    const hasTransactions = directTxCount > 0;
    const isExpandable = hasChildren || hasTransactions;

    // Progress bar color graded by usage
    const barColor = overLimit
        ? 'bg-destructive'
        : usedPct >= 80
          ? 'bg-warning'
          : 'bg-primary';

    return (
        <div
            className="rounded-xl border border-border bg-card shadow-sm transition-colors hover:border-border/80"
            style={{ marginLeft: depth * 16 }}
        >
            <button
                type="button"
                onClick={() => isExpandable && toggle(node.id)}
                disabled={!isExpandable}
                className={clsx(
                    'flex w-full items-center gap-2 px-4 py-3 text-left transition-colors',
                    isExpandable && 'hover:bg-muted/40',
                )}
            >
                {isExpandable ? (
                    open ? (
                        <ChevronDownIcon className="h-4 w-4 text-muted-foreground" />
                    ) : (
                        <ChevronRightIcon className="h-4 w-4 text-muted-foreground" />
                    )
                ) : (
                    <span className="w-4" />
                )}
                <FolderIcon className="h-4 w-4 text-primary" />
                <span className="truncate font-semibold text-foreground">{node.name}</span>

                {totalTxCount > 0 && (
                    <span className="hidden rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground sm:inline-block">
                        {totalTxCount} {totalTxCount === 1 ? 'tx' : 'txs'}
                    </span>
                )}

                <div className="ml-auto flex items-center gap-3">
                    <span className={clsx('font-semibold tabular-nums', total >= 0 ? 'text-success' : 'text-destructive')}>
                        <Sensitive>{`${total >= 0 ? '+' : '-'}E£ ${fmt(Math.abs(total))}`}</Sensitive>
                    </span>
                </div>
            </button>

            {limit > 0 && (
                <div className="px-4 pb-3">
                    <div className="h-1.5 w-full overflow-hidden rounded-full bg-muted">
                        <div
                            className={clsx('h-full transition-all duration-300', barColor)}
                            style={{ width: `${Math.min(usedRatio, 1) * 100}%` }}
                        />
                    </div>
                    <div className="mt-1 flex items-center justify-between text-xs text-muted-foreground">
                        <span className={clsx(overLimit && 'font-medium text-destructive')}>
                            {usedPct}% of limit{overLimit && ` (over by E£ ${fmt(Math.abs(total) - limit)})`}
                        </span>
                        <span className="tabular-nums">
                            <Sensitive>E£ {fmt(limit)}</Sensitive>
                        </span>
                    </div>
                </div>
            )}

            <Transition
                show={open && isExpandable}
                enter="transition-all duration-200 ease-out overflow-hidden"
                enterFrom="max-h-0 opacity-0"
                enterTo="max-h-[4000px] opacity-100"
                leave="transition-all duration-150 ease-in overflow-hidden"
                leaveFrom="max-h-[4000px] opacity-100"
                leaveTo="max-h-0 opacity-0"
            >
                <div>
                    {hasTransactions && (
                        <div className="border-t border-border px-4 py-2">
                            <ul className="divide-y divide-border">
                                {node.normal_transactions!.map((t) => (
                                    <TransactionRow key={t.id} tx={t} />
                                ))}
                            </ul>
                        </div>
                    )}

                    {hasChildren && (
                        <div className="space-y-2 border-t border-border px-4 py-2">
                            {node.children?.map((c) => (
                                <CategoryRow key={c.id} node={c} depth={depth + 1} />
                            ))}
                        </div>
                    )}
                </div>
            </Transition>
        </div>
    );
}

// ───── Transaction row ──────────────────────────────────────────────
function TransactionRow({ tx }: { tx: TxRow }) {
    const qty = Number(tx.quantity);
    const price = Number(tx.price);
    const total = qty * price;
    const isCredit = tx.direction === 'credit';
    const formattedDate = formatDayMonth(tx.date);
    const hasMultiple = qty > 1;
    const actions = useRowActions();

    return (
        <li className="group flex items-center gap-3 py-2 text-sm">
            <span
                className={clsx(
                    'h-1.5 w-1.5 flex-shrink-0 rounded-full',
                    isCredit ? 'bg-success' : 'bg-destructive',
                )}
                title={isCredit ? 'Credit' : 'Debit'}
            />
            <div className="min-w-0 flex-1">
                <div className="truncate text-foreground">{tx.name}</div>
                <div className="mt-0.5 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-muted-foreground">
                    {tx.wallet?.name && (
                        <span className="inline-flex items-center gap-1 truncate">
                            <WalletIcon className="h-3 w-3 flex-shrink-0" />
                            <span className="truncate">{tx.wallet.name}</span>
                        </span>
                    )}
                    {formattedDate && <span className="font-mono">{formattedDate}</span>}
                    {hasMultiple && (
                        <span className="font-mono" title="quantity × unit price">
                            {qty} × <Sensitive>E£ {fmt(price)}</Sensitive>
                        </span>
                    )}
                    {tx.comment && (
                        <span
                            className="inline-flex items-center gap-1 text-muted-foreground/80"
                            title={tx.comment}
                        >
                            <ChatBubbleLeftEllipsisIcon className="h-3 w-3" />
                            <span className="max-w-[10rem] truncate">{tx.comment}</span>
                        </span>
                    )}
                </div>
            </div>
            <span
                className={clsx(
                    'font-medium tabular-nums',
                    isCredit ? 'text-success' : 'text-destructive',
                )}
            >
                <Sensitive>{`${isCredit ? '+' : '-'}E£ ${fmt(total)}`}</Sensitive>
            </span>

            {actions && (
                <div className="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100 pointer-coarse:opacity-100">
                    <RowIconButton onClick={() => actions.onMoveToDraft(tx)} title="Move to drafts" tone="warning">
                        <ArrowUturnLeftIcon className="h-4 w-4" />
                    </RowIconButton>
                    <RowIconButton onClick={() => actions.onEdit(tx)} title="Edit">
                        <PencilSquareIcon className="h-4 w-4" />
                    </RowIconButton>
                    <RowIconButton onClick={() => actions.onDelete(tx)} title="Delete" danger>
                        <TrashIcon className="h-4 w-4" />
                    </RowIconButton>
                </div>
            )}
        </li>
    );
}

function RowIconButton({
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
                !danger && !tone && 'hover:text-primary',
            )}
        >
            {children}
        </button>
    );
}

function formatDayMonth(iso?: string): string | null {
    if (!iso) return null;
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) return iso;
    return d.toLocaleDateString(undefined, { day: 'numeric', month: 'short' });
}

// ───── UI primitives ────────────────────────────────────────────────
function IconButton({
    children,
    onClick,
    title,
}: {
    children: React.ReactNode;
    onClick: () => void;
    title: string;
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            title={title}
            className="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-primary"
        >
            {children}
        </button>
    );
}

function EmptyCard({
    icon: Icon,
    title,
    description,
}: {
    icon: typeof CalendarDaysIcon;
    title: string;
    description: string;
}) {
    return (
        <div className="rounded-xl border border-border bg-card p-10 text-center">
            <div className="mx-auto flex max-w-sm flex-col items-center gap-3">
                <div className="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                    <Icon className="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 className="text-base font-semibold text-foreground">{title}</h3>
                <p className="text-sm text-muted-foreground">{description}</p>
            </div>
        </div>
    );
}
