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
    MagnifyingGlassIcon,
    CheckCircleIcon,
    ClockIcon,
    PlayCircleIcon,
    Bars3Icon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronDoubleDownIcon,
    ChevronDoubleUpIcon,
    XMarkIcon,
    CalendarIcon,
    EyeSlashIcon,
    EyeIcon,
} from '@heroicons/react/24/outline';
import clsx from 'clsx';
import {
    DndContext,
    DragEndEvent,
    PointerSensor,
    closestCenter,
    useSensor,
    useSensors,
} from '@dnd-kit/core';
import {
    SortableContext,
    arrayMove,
    useSortable,
    verticalListSortingStrategy,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
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
import Pagination from '@/Components/Pagination';
import Modal from '@/Components/Modal';
import ConfirmModal from '@/Components/ConfirmModal';
import { toast } from '@/Components/FlashBanner';
import { MonthYear, Paginator, Todo, TodoPriority, TodoScope, TodoStatus } from '@/types';

type TodoNode = Todo & {
    all_children?: TodoNode[];
    user?: { first_name: string; last_name: string };
};

type Filters = {
    title?: string;
    status?: string;
    priority?: string;
    scope?: string;
    month_year_id?: string;
};

type Props = {
    todos: Paginator<TodoNode>;
    filters: Filters;
    options: {
        month_years: Pick<MonthYear, 'id' | 'month' | 'year'>[];
        allTodos: { id: number; title: string; parent_id: number | null }[];
    };
};

const EXPANSION_KEY = 'todos.expanded';
const HIDE_COMPLETED_KEY = 'todos.hide_completed';

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
    statusOf: (todo: TodoNode) => TodoStatus;
    setStatus: (todo: TodoNode, status: TodoStatus) => void;
};

const StatusContext = createContext<StatusApi | null>(null);

function StatusProvider({ children }: PropsWithChildren) {
    const [overrides, setOverrides] = useState<Map<number, TodoStatus>>(new Map());

    const api: StatusApi = useMemo(() => ({
        statusOf: (t) => overrides.get(t.id) ?? t.status,
        setStatus: (t, next) => {
            if ((overrides.get(t.id) ?? t.status) === next) return;
            setOverrides((prev) => new Map(prev).set(t.id, next));
            axios
                .post(`/todos/${t.id}/toggle`, { status: next })
                .then(() => router.reload({ only: ['todos'] }))
                .catch(() => {
                    setOverrides((prev) => {
                        const m = new Map(prev);
                        m.delete(t.id);
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

// ───── Helpers ──────────────────────────────────────────────────────
function walkIds(items: TodoNode[]): number[] {
    const out: number[] = [];
    const visit = (n: TodoNode) => {
        out.push(n.id);
        (n.all_children ?? []).forEach(visit);
    };
    items.forEach(visit);
    return out;
}

function countSubtree(node: TodoNode, statusOf: (n: TodoNode) => TodoStatus): { total: number; done: number } {
    let total = 0;
    let done = 0;
    const visit = (n: TodoNode) => {
        for (const c of n.all_children ?? []) {
            total += 1;
            if (statusOf(c) === 'completed') done += 1;
            visit(c);
        }
    };
    visit(node);
    return { total, done };
}

function formatDueDate(iso: string): { label: string; overdue: boolean; today: boolean } {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const due = new Date(iso);
    due.setHours(0, 0, 0, 0);
    const diffDays = Math.round((due.getTime() - today.getTime()) / (1000 * 60 * 60 * 24));

    const rtf = new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' });
    let label: string;
    if (diffDays === 0) label = 'Today';
    else if (Math.abs(diffDays) < 30) label = rtf.format(diffDays, 'day');
    else if (Math.abs(diffDays) < 365) label = rtf.format(Math.round(diffDays / 30), 'month');
    else label = rtf.format(Math.round(diffDays / 365), 'year');

    return { label, overdue: diffDays < 0, today: diffDays === 0 };
}

function useDebouncedEffect(fn: () => void, deps: unknown[], delay: number) {
    useEffect(() => {
        const t = setTimeout(fn, delay);
        return () => clearTimeout(t);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, deps);
}

// ───── Page ─────────────────────────────────────────────────────────
export default function TodosIndex(props: Props) {
    return (
        <ExpansionProvider>
            <StatusProvider>
                <TodosPage {...props} />
            </StatusProvider>
        </ExpansionProvider>
    );
}

function TodosPage({ todos, filters, options }: Props) {
    const expansion = useExpansion();

    const [filtersOpen, setFiltersOpen] = useState(false);
    const [formOpen, setFormOpen] = useState<
        | { mode: 'create'; parent?: TodoNode; initialTitle?: string }
        | { mode: 'edit'; todo: TodoNode }
        | null
    >(null);
    const [deleteTarget, setDeleteTarget] = useState<TodoNode | null>(null);
    const [hideCompleted, setHideCompleted] = useState<boolean>(
        () => typeof window !== 'undefined' && window.localStorage.getItem(HIDE_COMPLETED_KEY) === '1',
    );

    const titleInputRef = useRef<HTMLInputElement>(null);

    const filterForm = useForm({
        title: filters.title ?? '',
        status: filters.status ?? '',
        priority: filters.priority ?? '',
        scope: filters.scope ?? '',
        month_year_id: filters.month_year_id ?? '',
    });

    const hasActiveFilters = Object.values(filterForm.data).some(Boolean);

    // Live search on title (debounced)
    useDebouncedEffect(
        () => {
            if (filterForm.data.title === (filters.title ?? '')) return;
            router.get('/todos', filterForm.data, {
                preserveState: true,
                preserveScroll: true,
                only: ['todos', 'filters'],
                replace: true,
            });
        },
        [filterForm.data.title],
        300,
    );

    const apply = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/todos', filterForm.data, { preserveState: true, preserveScroll: true });
    };

    const clearFilter = (key: keyof Filters) => {
        const next = { ...filterForm.data, [key]: '' };
        filterForm.setData(next);
        router.get('/todos', next, { preserveState: true, preserveScroll: true });
    };

    const clearAll = () => {
        filterForm.reset();
        router.get('/todos', {}, { preserveState: true, preserveScroll: true });
    };

    const toggleHideCompleted = (v: boolean) => {
        setHideCompleted(v);
        try {
            window.localStorage.setItem(HIDE_COMPLETED_KEY, v ? '1' : '0');
        } catch {
            /* ignore */
        }
    };

    // Auto-focus title input when filter bar opens
    useEffect(() => {
        if (filtersOpen) {
            const t = setTimeout(() => titleInputRef.current?.focus(), 50);
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

    const expandAll = () => expansion.setMany(walkIds(todos.data), true);
    const collapseAll = () => expansion.clear();

    return (
        <AuthenticatedLayout>
            <Head title="Todos" />

            <PageHeader
                title="Todos"
                description="Track tasks and reminders"
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
                            <PlusIcon className="mr-1.5 h-4 w-4" /> Add Todo
                        </PrimaryButton>
                    </>
                }
            />

            {hasActiveFilters && (
                <div className="mb-4 flex flex-wrap items-center gap-2">
                    {filterForm.data.title && (
                        <FilterChip label={`Title: ${filterForm.data.title}`} onClear={() => clearFilter('title')} />
                    )}
                    {filterForm.data.status && (
                        <FilterChip label={`Status: ${filterForm.data.status.replace('_', ' ')}`} onClear={() => clearFilter('status')} />
                    )}
                    {filterForm.data.priority && (
                        <FilterChip label={`Priority: ${filterForm.data.priority}`} onClear={() => clearFilter('priority')} />
                    )}
                    {filterForm.data.scope && (
                        <FilterChip label={`Scope: ${filterForm.data.scope}`} onClear={() => clearFilter('scope')} />
                    )}
                    {filterForm.data.month_year_id && (
                        <FilterChip
                            label={`Month: ${options.month_years.find((m) => String(m.id) === filterForm.data.month_year_id)
                                ? `${options.month_years.find((m) => String(m.id) === filterForm.data.month_year_id)!.year}-${String(options.month_years.find((m) => String(m.id) === filterForm.data.month_year_id)!.month).padStart(2, '0')}`
                                : filterForm.data.month_year_id}`}
                            onClear={() => clearFilter('month_year_id')}
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
                <form onSubmit={apply} className="mb-4 rounded-xl border border-border bg-card p-4">
                    <div className="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                        <FormField label="Title">
                            <TextInput
                                ref={titleInputRef as unknown as React.Ref<HTMLInputElement>}
                                value={filterForm.data.title}
                                onChange={(e) => filterForm.setData('title', e.target.value)}
                                placeholder="Search…"
                            />
                        </FormField>
                        <FormField label="Status">
                            <Select
                                value={filterForm.data.status}
                                onChange={(e) => filterForm.setData('status', e.target.value)}
                                placeholder="All"
                                options={[
                                    { value: 'pending', label: 'Pending' },
                                    { value: 'in_progress', label: 'In progress' },
                                    { value: 'completed', label: 'Completed' },
                                ]}
                            />
                        </FormField>
                        <FormField label="Priority">
                            <Select
                                value={filterForm.data.priority}
                                onChange={(e) => filterForm.setData('priority', e.target.value)}
                                placeholder="All"
                                options={[
                                    { value: 'low', label: 'Low' },
                                    { value: 'medium', label: 'Medium' },
                                    { value: 'high', label: 'High' },
                                ]}
                            />
                        </FormField>
                        <FormField label="Scope">
                            <Select
                                value={filterForm.data.scope}
                                onChange={(e) => filterForm.setData('scope', e.target.value)}
                                placeholder="All"
                                options={[
                                    { value: 'public', label: 'Public' },
                                    { value: 'private', label: 'Private' },
                                ]}
                            />
                        </FormField>
                        <FormField label="Month">
                            <Select
                                value={filterForm.data.month_year_id}
                                onChange={(e) => filterForm.setData('month_year_id', e.target.value)}
                                placeholder="All"
                                options={options.month_years.map((m) => ({
                                    value: m.id,
                                    label: `${m.year}-${String(m.month).padStart(2, '0')}`,
                                }))}
                            />
                        </FormField>
                        <div className="flex items-end">
                            <PrimaryButton>Apply</PrimaryButton>
                        </div>
                    </div>
                    <div className="mt-3 flex items-center gap-2 border-t border-border pt-3 text-sm text-muted-foreground">
                        {hideCompleted ? <EyeSlashIcon className="h-4 w-4" /> : <EyeIcon className="h-4 w-4" />}
                        <span>Hide completed</span>
                        <Switch
                            checked={hideCompleted}
                            onChange={toggleHideCompleted}
                            className={clsx(
                                'relative inline-flex h-5 w-9 items-center rounded-full transition-colors',
                                hideCompleted ? 'bg-primary' : 'bg-muted',
                            )}
                        >
                            <span
                                className={clsx(
                                    'inline-block h-3.5 w-3.5 transform rounded-full bg-card transition-transform',
                                    hideCompleted ? 'translate-x-5' : 'translate-x-1',
                                )}
                            />
                        </Switch>
                    </div>
                </form>
            )}

            <QuickAdd onCreate={(title) => setFormOpen({ mode: 'create', initialTitle: title })} />

            <SortableTodoList
                items={todos.data}
                parentId={null}
                hideCompleted={hideCompleted}
                onEdit={(t) => setFormOpen({ mode: 'edit', todo: t })}
                onAddChild={(parent) => setFormOpen({ mode: 'create', parent })}
                onDelete={(t) => setDeleteTarget(t)}
                emptyState={
                    hasActiveFilters ? (
                        <EmptyState
                            title="No todos match your filters"
                            description="Adjust or clear the filters to see more results."
                            cta={<SecondaryButton onClick={clearAll}>Clear filters</SecondaryButton>}
                        />
                    ) : (
                        <EmptyState
                            title="No todos yet"
                            description="Quick-add one above, or click Add Todo for the full form."
                            cta={
                                <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                                    <PlusIcon className="mr-1.5 h-4 w-4" /> Add your first todo
                                </PrimaryButton>
                            }
                        />
                    )
                }
            />

            <div className="mt-4">
                <Pagination links={todos.links} />
            </div>

            <TodoFormModal
                show={!!formOpen}
                onClose={() => setFormOpen(null)}
                mode={formOpen?.mode ?? 'create'}
                todo={formOpen?.mode === 'edit' ? formOpen.todo : undefined}
                defaultParent={formOpen?.mode === 'create' ? formOpen.parent : undefined}
                initialTitle={formOpen?.mode === 'create' ? formOpen.initialTitle : undefined}
                allTodos={options.allTodos}
                monthYears={options.month_years}
            />

            <ConfirmModal
                show={!!deleteTarget}
                title="Delete todo?"
                message={deleteTarget ? `Delete "${deleteTarget.title}"? Children will also be removed.` : ''}
                onClose={() => setDeleteTarget(null)}
                onConfirm={() => {
                    if (!deleteTarget) return;
                    router.delete(`/todos/${deleteTarget.id}`, {
                        onSuccess: () => setDeleteTarget(null),
                        preserveScroll: true,
                    });
                }}
            />
        </AuthenticatedLayout>
    );
}

// ───── Quick-add input ──────────────────────────────────────────────
function QuickAdd({ onCreate }: { onCreate: (title: string) => void }) {
    const [value, setValue] = useState('');
    const [submitting, setSubmitting] = useState(false);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        const title = value.trim();
        if (!title) return;
        setSubmitting(true);
        axios
            .post('/todos', { title, scope: 'public', priority: 'medium', status: 'pending' })
            .then(() => {
                setValue('');
                router.reload({ only: ['todos', 'options'] });
            })
            .catch(() => toast('Could not create todo.', 'error'))
            .finally(() => setSubmitting(false));
    };

    const handleKey = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Enter' && e.shiftKey) {
            e.preventDefault();
            onCreate(value);
        }
    };

    return (
        <form onSubmit={submit} className="mb-3 flex items-center gap-2 rounded-xl border border-border bg-card px-3 py-2 shadow-sm">
            <PlusIcon className="h-4 w-4 text-muted-foreground" />
            <input
                type="text"
                value={value}
                disabled={submitting}
                onChange={(e) => setValue(e.target.value)}
                onKeyDown={handleKey}
                placeholder="Quick add a todo… (Enter to save · Shift+Enter for full form)"
                className="block flex-1 border-0 bg-transparent p-1 text-sm text-foreground placeholder:text-muted-foreground focus:ring-0"
            />
            {value.trim() && (
                <button
                    type="button"
                    onClick={() => onCreate(value)}
                    className="rounded p-1 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    title="Open full form"
                >
                    <PencilSquareIcon className="h-4 w-4" />
                </button>
            )}
        </form>
    );
}

// ───── Sortable list ────────────────────────────────────────────────
function SortableTodoList({
    items,
    parentId,
    hideCompleted,
    onEdit,
    onAddChild,
    onDelete,
    emptyState,
}: {
    items: TodoNode[];
    parentId: number | null;
    hideCompleted: boolean;
    onEdit: (t: TodoNode) => void;
    onAddChild: (parent: TodoNode) => void;
    onDelete: (t: TodoNode) => void;
    emptyState?: React.ReactNode;
}) {
    const sensors = useSensors(useSensor(PointerSensor, { activationConstraint: { distance: 5 } }));
    const [localItems, setLocalItems] = useState(items);
    const { statusOf } = useStatus();

    useEffect(() => setLocalItems(items), [items]);

    const visibleItems = hideCompleted
        ? localItems.filter((t) => statusOf(t) !== 'completed')
        : localItems;

    const handleDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;
        if (!over || active.id === over.id) return;
        const oldIdx = localItems.findIndex((t) => String(t.id) === String(active.id));
        const newIdx = localItems.findIndex((t) => String(t.id) === String(over.id));
        if (oldIdx === -1 || newIdx === -1) return;

        const next = arrayMove(localItems, oldIdx, newIdx);
        setLocalItems(next);

        const orders: Record<number, number> = {};
        next.forEach((t, i) => { orders[t.id] = i; });
        axios.post('/todos/reorder', { orders, parent_id: parentId, todo_id: active.id }).catch(() => {
            toast('Could not save the new order.', 'error');
            router.reload({ only: ['todos'] });
        });
    };

    if (visibleItems.length === 0 && emptyState) {
        return <>{emptyState}</>;
    }

    return (
        <DndContext sensors={sensors} collisionDetection={closestCenter} onDragEnd={handleDragEnd}>
            <SortableContext items={visibleItems.map((i) => String(i.id))} strategy={verticalListSortingStrategy}>
                <div className="space-y-2">
                    {visibleItems.map((t) => (
                        <SortableTodoItem
                            key={t.id}
                            todo={t}
                            hideCompleted={hideCompleted}
                            onEdit={onEdit}
                            onAddChild={onAddChild}
                            onDelete={onDelete}
                        />
                    ))}
                </div>
            </SortableContext>
        </DndContext>
    );
}

// ───── Row ──────────────────────────────────────────────────────────
function SortableTodoItem({
    todo,
    depth = 0,
    hideCompleted,
    onEdit,
    onAddChild,
    onDelete,
}: {
    todo: TodoNode;
    depth?: number;
    hideCompleted: boolean;
    onEdit: (t: TodoNode) => void;
    onAddChild: (parent: TodoNode) => void;
    onDelete: (t: TodoNode) => void;
}) {
    const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({ id: String(todo.id) });
    const { isExpanded, toggle } = useExpansion();
    const { statusOf } = useStatus();
    const open = isExpanded(todo.id);

    const children = todo.all_children ?? [];
    const hasChildren = children.length > 0;
    const status = statusOf(todo);
    const isCompleted = status === 'completed';

    const subtree = useMemo(() => countSubtree(todo, statusOf), [todo, statusOf]);

    const style = { transform: CSS.Transform.toString(transform), transition, opacity: isDragging ? 0.5 : 1 };

    return (
        <div
            ref={setNodeRef}
            style={{ ...style, marginLeft: depth * 16 }}
            className="group rounded-xl border border-border bg-card shadow-sm transition-colors hover:border-border/80"
        >
            <div className="flex items-center gap-2 px-3 py-2">
                <button
                    {...attributes}
                    {...listeners}
                    className="cursor-grab rounded p-1 text-muted-foreground opacity-0 transition-opacity hover:bg-muted focus-visible:opacity-100 active:cursor-grabbing group-hover:opacity-100 pointer-coarse:opacity-100"
                    title="Drag to reorder"
                >
                    <Bars3Icon className="h-4 w-4" />
                </button>

                {hasChildren ? (
                    <button
                        type="button"
                        onClick={() => toggle(todo.id)}
                        className="text-muted-foreground transition-colors hover:text-foreground"
                        title={open ? 'Collapse' : 'Expand'}
                    >
                        {open ? <ChevronDownIcon className="h-4 w-4" /> : <ChevronRightIcon className="h-4 w-4" />}
                    </button>
                ) : (
                    <span className="w-4" />
                )}

                <StatusMenu status={status} todo={todo} />

                <div className="min-w-0 flex-1">
                    <div
                        className={clsx(
                            'truncate text-sm font-medium',
                            isCompleted ? 'text-muted-foreground line-through' : 'text-foreground',
                        )}
                    >
                        {todo.title}
                    </div>
                    {todo.description && (
                        <div className="truncate text-xs text-muted-foreground">{todo.description}</div>
                    )}
                </div>

                {todo.due_date && <DueDateLabel iso={todo.due_date} active={!isCompleted} />}

                {!open && hasChildren && subtree.total > 0 && (
                    <SubtreeProgress total={subtree.total} done={subtree.done} />
                )}

                <PriorityBadge priority={todo.priority} />
                <ScopeBadge scope={todo.scope} />

                <div className="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100 focus-within:opacity-100 pointer-coarse:opacity-100">
                    <IconButton onClick={() => onAddChild(todo)} title="Add subtask">
                        <PlusIcon className="h-4 w-4" />
                    </IconButton>
                    <IconButton onClick={() => onEdit(todo)} title="Edit">
                        <PencilSquareIcon className="h-4 w-4" />
                    </IconButton>
                    <IconButton onClick={() => onDelete(todo)} title="Delete" danger>
                        <TrashIcon className="h-4 w-4" />
                    </IconButton>
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
                <div className="border-t border-border px-3 pb-2 pt-2">
                    <ChildList
                        items={children}
                        parentId={todo.id}
                        depth={depth + 1}
                        hideCompleted={hideCompleted}
                        onEdit={onEdit}
                        onAddChild={onAddChild}
                        onDelete={onDelete}
                    />
                </div>
            </Transition>
        </div>
    );
}

function ChildList({
    items,
    parentId,
    depth,
    hideCompleted,
    onEdit,
    onAddChild,
    onDelete,
}: {
    items: TodoNode[];
    parentId: number;
    depth: number;
    hideCompleted: boolean;
    onEdit: (t: TodoNode) => void;
    onAddChild: (parent: TodoNode) => void;
    onDelete: (t: TodoNode) => void;
}) {
    const sensors = useSensors(useSensor(PointerSensor, { activationConstraint: { distance: 5 } }));
    const [local, setLocal] = useState(items);
    const { statusOf } = useStatus();

    useEffect(() => setLocal(items), [items]);

    const visible = hideCompleted ? local.filter((t) => statusOf(t) !== 'completed') : local;

    const onDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;
        if (!over || active.id === over.id) return;
        const oldIdx = local.findIndex((t) => String(t.id) === String(active.id));
        const newIdx = local.findIndex((t) => String(t.id) === String(over.id));
        if (oldIdx === -1 || newIdx === -1) return;
        const next = arrayMove(local, oldIdx, newIdx);
        setLocal(next);
        const orders: Record<number, number> = {};
        next.forEach((t, i) => { orders[t.id] = i; });
        axios.post('/todos/reorder', { orders, parent_id: parentId, todo_id: active.id }).catch(() => {
            toast('Could not save the new order.', 'error');
            router.reload({ only: ['todos'] });
        });
    };

    return (
        <DndContext sensors={sensors} collisionDetection={closestCenter} onDragEnd={onDragEnd}>
            <SortableContext items={visible.map((i) => String(i.id))} strategy={verticalListSortingStrategy}>
                <div className="space-y-2">
                    {visible.map((c) => (
                        <SortableTodoItem
                            key={c.id}
                            todo={c}
                            depth={depth}
                            hideCompleted={hideCompleted}
                            onEdit={onEdit}
                            onAddChild={onAddChild}
                            onDelete={onDelete}
                        />
                    ))}
                </div>
            </SortableContext>
        </DndContext>
    );
}

// ───── Inline widgets ───────────────────────────────────────────────
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
                    <CheckCircleIcon className="h-8 w-8 text-muted-foreground" />
                </div>
                <h3 className="text-base font-semibold text-foreground">{title}</h3>
                <p className="text-sm text-muted-foreground">{description}</p>
                {cta}
            </div>
        </div>
    );
}

function SubtreeProgress({ total, done }: { total: number; done: number }) {
    const pct = total ? (done / total) * 100 : 0;
    return (
        <div className="hidden flex-shrink-0 items-center gap-2 sm:flex">
            <span className="font-mono text-xs text-muted-foreground">{done}/{total}</span>
            <div className="h-1 w-12 overflow-hidden rounded-full bg-muted">
                <div className="h-full bg-success transition-all" style={{ width: `${pct}%` }} />
            </div>
        </div>
    );
}

function DueDateLabel({ iso, active }: { iso: string; active: boolean }) {
    const { label, overdue, today } = formatDueDate(iso);
    const isOverdueActive = overdue && active;
    return (
        <span
            className={clsx(
                'hidden items-center gap-1 rounded-md px-1.5 py-0.5 text-xs font-medium sm:inline-flex',
                isOverdueActive
                    ? 'bg-destructive/10 text-destructive'
                    : today
                      ? 'bg-warning/10 text-warning'
                      : 'text-muted-foreground',
            )}
            title={iso}
        >
            <CalendarIcon className="h-3.5 w-3.5" />
            {label}
        </span>
    );
}

// ───── Status menu (popover with explicit picks) ────────────────────
const STATUS_META: Record<TodoStatus, { icon: typeof ClockIcon; label: string; color: string }> = {
    pending: { icon: ClockIcon, label: 'Pending', color: 'text-muted-foreground' },
    in_progress: { icon: PlayCircleIcon, label: 'In progress', color: 'text-warning' },
    completed: { icon: CheckCircleIcon, label: 'Completed', color: 'text-success' },
};

function StatusMenu({ status, todo }: { status: TodoStatus; todo: TodoNode }) {
    const { setStatus } = useStatus();
    const Current = STATUS_META[status].icon;

    return (
        <Menu as="div" className="relative">
            <MenuButton
                title={STATUS_META[status].label}
                className={clsx('rounded-md p-1.5 transition-colors hover:bg-muted', STATUS_META[status].color)}
            >
                <Current className="h-5 w-5" />
            </MenuButton>
            <Transition
                enter="transition ease-out duration-100"
                enterFrom="opacity-0 scale-95"
                enterTo="opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="opacity-100 scale-100"
                leaveTo="opacity-0 scale-95"
            >
                <MenuItems className="absolute left-0 z-20 mt-1 w-40 origin-top-left overflow-hidden rounded-md border border-border bg-popover py-1 text-sm shadow-lg ring-1 ring-foreground/5 focus:outline-none">
                    {(Object.keys(STATUS_META) as TodoStatus[]).map((s) => {
                        const meta = STATUS_META[s];
                        const Icon = meta.icon;
                        const active = s === status;
                        return (
                            <MenuItem key={s}>
                                {({ focus }) => (
                                    <button
                                        type="button"
                                        onClick={() => setStatus(todo, s)}
                                        className={clsx(
                                            'flex w-full items-center gap-2 px-3 py-1.5 text-left text-popover-foreground transition-colors',
                                            focus && 'bg-muted',
                                        )}
                                    >
                                        <Icon className={clsx('h-4 w-4', meta.color)} />
                                        <span className="flex-1">{meta.label}</span>
                                        {active && <CheckCircleIcon className="h-4 w-4 text-primary" />}
                                    </button>
                                )}
                            </MenuItem>
                        );
                    })}
                </MenuItems>
            </Transition>
        </Menu>
    );
}

// ───── Badges ───────────────────────────────────────────────────────
function PriorityBadge({ priority }: { priority: TodoPriority }) {
    const palette = {
        low: 'bg-muted text-foreground',
        medium: 'bg-warning/15 text-warning',
        high: 'bg-destructive/15 text-destructive',
    } as const;
    return (
        <span className={clsx('hidden rounded-full px-2 py-0.5 text-xs font-medium capitalize sm:inline-block', palette[priority])}>
            {priority}
        </span>
    );
}

function ScopeBadge({ scope }: { scope: TodoScope }) {
    return (
        <span
            className={clsx(
                'hidden rounded-full px-2 py-0.5 text-xs font-medium sm:inline-block',
                scope === 'public' ? 'bg-primary/15 text-primary' : 'bg-muted text-foreground',
            )}
        >
            {scope}
        </span>
    );
}

// ───── Form modal ───────────────────────────────────────────────────
function TodoFormModal({
    show,
    onClose,
    mode,
    todo,
    defaultParent,
    initialTitle,
    allTodos,
    monthYears,
}: {
    show: boolean;
    onClose: () => void;
    mode: 'create' | 'edit';
    todo?: TodoNode;
    defaultParent?: TodoNode;
    initialTitle?: string;
    allTodos: { id: number; title: string; parent_id: number | null }[];
    monthYears: Pick<MonthYear, 'id' | 'month' | 'year'>[];
}) {
    const form = useForm({
        title: todo?.title ?? initialTitle ?? '',
        description: todo?.description ?? '',
        status: (todo?.status ?? 'pending') as TodoStatus,
        priority: (todo?.priority ?? 'medium') as TodoPriority,
        scope: (todo?.scope ?? 'public') as TodoScope,
        due_date: todo?.due_date ?? '',
        parent_id: (todo?.parent_id ?? defaultParent?.id ?? '')?.toString() ?? '',
        month_year_id: (todo?.month_year_id ?? '')?.toString() ?? '',
        order: todo?.order ?? 0,
    });

    useEffect(() => {
        if (show) {
            form.setData({
                title: todo?.title ?? initialTitle ?? '',
                description: todo?.description ?? '',
                status: (todo?.status ?? 'pending') as TodoStatus,
                priority: (todo?.priority ?? 'medium') as TodoPriority,
                scope: (todo?.scope ?? 'public') as TodoScope,
                due_date: todo?.due_date ?? '',
                parent_id: (todo?.parent_id ?? defaultParent?.id ?? '')?.toString() ?? '',
                month_year_id: (todo?.month_year_id ?? '')?.toString() ?? '',
                order: todo?.order ?? 0,
            });
            form.clearErrors();
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [show, todo?.id, defaultParent?.id, initialTitle]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const opts = { onSuccess: () => onClose(), preserveScroll: true };
        if (mode === 'edit' && todo) form.put(`/todos/${todo.id}`, opts);
        else form.post('/todos', opts);
    };

    return (
        <Modal show={show} onClose={onClose} maxWidth="lg">
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
                    {mode === 'edit'
                        ? 'Edit todo'
                        : defaultParent
                          ? `Add subtask under "${defaultParent.title}"`
                          : 'New todo'}
                </h3>

                <div className="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div className="sm:col-span-2">
                        <FormField label="Title" htmlFor="todo_title" error={form.errors.title}>
                            <TextInput
                                id="todo_title"
                                value={form.data.title}
                                onChange={(e) => form.setData('title', e.target.value)}
                                required
                                isFocused
                            />
                        </FormField>
                    </div>
                    <div className="sm:col-span-2">
                        <FormField label="Description" htmlFor="todo_desc" error={form.errors.description}>
                            <textarea
                                id="todo_desc"
                                rows={2}
                                className="block w-full rounded-md border-input bg-card text-foreground shadow-sm focus:border-ring focus:ring-ring"
                                value={form.data.description ?? ''}
                                onChange={(e) => form.setData('description', e.target.value)}
                            />
                        </FormField>
                    </div>

                    <FormField label="Status" htmlFor="todo_status" error={form.errors.status}>
                        <Select
                            id="todo_status"
                            value={form.data.status}
                            onChange={(e) => form.setData('status', e.target.value as TodoStatus)}
                            options={[
                                { value: 'pending', label: 'Pending' },
                                { value: 'in_progress', label: 'In progress' },
                                { value: 'completed', label: 'Completed' },
                            ]}
                        />
                    </FormField>

                    <FormField label="Priority" htmlFor="todo_priority" error={form.errors.priority}>
                        <Select
                            id="todo_priority"
                            value={form.data.priority}
                            onChange={(e) => form.setData('priority', e.target.value as TodoPriority)}
                            options={[
                                { value: 'low', label: 'Low' },
                                { value: 'medium', label: 'Medium' },
                                { value: 'high', label: 'High' },
                            ]}
                            required
                        />
                    </FormField>

                    <FormField label="Scope" htmlFor="todo_scope" error={form.errors.scope}>
                        <Select
                            id="todo_scope"
                            value={form.data.scope}
                            onChange={(e) => form.setData('scope', e.target.value as TodoScope)}
                            options={[
                                { value: 'public', label: 'Public (family)' },
                                { value: 'private', label: 'Private (you only)' },
                            ]}
                            required
                        />
                    </FormField>

                    <FormField label="Due date (optional)" htmlFor="todo_due" error={form.errors.due_date}>
                        <TextInput
                            id="todo_due"
                            type="date"
                            value={form.data.due_date ?? ''}
                            onChange={(e) => form.setData('due_date', e.target.value)}
                        />
                    </FormField>

                    <FormField label="Parent (optional)" error={form.errors.parent_id}>
                        <ParentCombobox
                            value={form.data.parent_id}
                            onChange={(v) => form.setData('parent_id', v)}
                            options={allTodos.filter((t) => !todo || t.id !== todo.id)}
                        />
                    </FormField>

                    <FormField label="Month (optional)" htmlFor="todo_month" error={form.errors.month_year_id}>
                        <Select
                            id="todo_month"
                            value={form.data.month_year_id}
                            onChange={(e) => form.setData('month_year_id', e.target.value)}
                            placeholder="None"
                            options={monthYears.map((m) => ({
                                value: m.id,
                                label: `${m.year}-${String(m.month).padStart(2, '0')}`,
                            }))}
                        />
                    </FormField>
                </div>

                <div className="mt-6 flex items-center justify-between gap-3">
                    <span className="text-xs text-muted-foreground">Tip: press ⌘/Ctrl+Enter to save</span>
                    <div className="flex gap-3">
                        <SecondaryButton type="button" onClick={onClose} disabled={form.processing}>Cancel</SecondaryButton>
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
    options: { id: number; title: string }[];
}) {
    const [query, setQuery] = useState('');
    const selected = options.find((o) => String(o.id) === value) ?? null;

    const filtered = query
        ? options.filter((o) => o.title.toLowerCase().includes(query.toLowerCase()))
        : options;

    return (
        <Combobox<{ id: number; title: string } | null>
            value={selected}
            onChange={(v) => onChange(v ? String(v.id) : '')}
            nullable
        >
            <div className="relative">
                <ComboboxInput
                    onChange={(e) => setQuery(e.target.value)}
                    displayValue={(v: { id: number; title: string } | null) => v?.title ?? ''}
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
                                            {o.title}
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
