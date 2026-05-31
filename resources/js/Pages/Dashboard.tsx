import { Fragment, PropsWithChildren, useEffect, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import {
    WalletIcon,
    ArrowTrendingUpIcon,
    ArrowTrendingDownIcon,
    PlusIcon,
    MinusCircleIcon,
    PlusCircleIcon,
    CalendarDaysIcon,
    EyeIcon,
    EyeSlashIcon,
    PencilSquareIcon,
    TrashIcon,
} from '@heroicons/react/24/outline';
import clsx from 'clsx';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import MonthYearFormModal from '@/Components/MonthYearFormModal';
import ConfirmModal from '@/Components/ConfirmModal';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import PageHeader from '@/Components/PageHeader';
import { Paginator } from '@/types';

type MonthRow = {
    id: number;
    month: number;
    year: number;
    month_year: string;
    credit: string | number;
    debit: string | number;
};

type WalletRow = {
    wallet_id: number | null;
    wallet_name: string | null;
    credit: string | number;
    debit: string | number;
};

type Props = {
    monthYears: Paginator<MonthRow>;
    wallets: Record<string, WalletRow[]>;
    totals: { credit: number; debit: number; balance: number };
};

const fmt = (v: string | number) => {
    const n = typeof v === 'string' ? parseFloat(v) : v;
    return new Intl.NumberFormat('en-EG', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n || 0);
};

type Accent = 'success' | 'danger' | 'primary' | 'warning';

const ACCENT_CLASSES: Record<Accent, string> = {
    success: 'bg-success/15 text-success',
    danger: 'bg-destructive/15 text-destructive',
    primary: 'bg-primary/15 text-primary',
    warning: 'bg-warning/15 text-warning',
};

const PRIVACY_KEY = 'dashboard.numbers_visible';

export default function Dashboard({ monthYears, wallets, totals }: Props) {
    const [expanded, setExpanded] = useState<Set<number>>(new Set());
    const [modalOpen, setModalOpen] = useState(false);
    const [editTarget, setEditTarget] = useState<MonthRow | null>(null);
    const [deleteTarget, setDeleteTarget] = useState<MonthRow | null>(null);

    // Sensitivity: default hidden on every fresh page load; toggle persists for the session only.
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

    const toggle = (id: number) => {
        const next = new Set(expanded);
        if (next.has(id)) next.delete(id);
        else next.add(id);
        setExpanded(next);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />
            <MonthYearFormModal show={modalOpen} onClose={() => setModalOpen(false)} />
            <MonthYearFormModal
                show={!!editTarget}
                monthYear={editTarget ? { id: editTarget.id, year: editTarget.year, month: editTarget.month } : null}
                onClose={() => setEditTarget(null)}
            />
            <ConfirmModal
                show={!!deleteTarget}
                title="Delete month?"
                message={deleteTarget ? `Delete ${deleteTarget.month_year}? Months with transactions can't be deleted — remove or reassign them first.` : ''}
                onClose={() => setDeleteTarget(null)}
                onConfirm={() => {
                    if (!deleteTarget) return;
                    router.delete(`/month_years/${deleteTarget.id}`, {
                        preserveScroll: true,
                        onFinish: () => setDeleteTarget(null),
                    });
                }}
            />

            <div className="mx-auto max-w-7xl">
                <PageHeader
                    title="Dashboard"
                    description="Overview of your financial journey"
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
                            <PrimaryButton onClick={() => setModalOpen(true)}>
                                <PlusIcon className="mr-1.5 h-4 w-4" /> Add Month
                            </PrimaryButton>
                        </>
                    }
                />

                <div className="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <SummaryCard
                        label="Total Balance"
                        value={`E£ ${fmt(totals.balance)}`}
                        valueClass={totals.balance >= 0 ? 'text-success' : 'text-destructive'}
                        icon={WalletIcon}
                        accent={totals.balance >= 0 ? 'success' : 'danger'}
                        badge={totals.balance >= 0 ? 'Healthy' : 'Deficit'}
                        sensitive={!numbersVisible}
                    />
                    <SummaryCard
                        label="Total Income"
                        value={`E£ ${fmt(totals.credit)}`}
                        valueClass="text-foreground"
                        icon={ArrowTrendingUpIcon}
                        accent="primary"
                        footnote="Total earnings to date"
                        sensitive={!numbersVisible}
                    />
                    <SummaryCard
                        label="Total Expenses"
                        value={`E£ ${fmt(totals.debit)}`}
                        valueClass="text-foreground"
                        icon={ArrowTrendingDownIcon}
                        accent="warning"
                        footnote="Total spending to date"
                        sensitive={!numbersVisible}
                    />
                </div>

                <div className="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
                    <div className="border-b border-border bg-muted/40 px-4 py-3">
                        <h2 className="font-semibold text-foreground">Monthly History</h2>
                    </div>

                    {monthYears.data.length === 0 ? (
                        <div className="flex flex-col items-center justify-center px-6 py-16">
                            <div className="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-muted">
                                <CalendarDaysIcon className="h-10 w-10 text-muted-foreground" />
                            </div>
                            <h3 className="text-base font-semibold text-foreground">No data yet</h3>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Start tracking your finances by adding a new month.
                            </p>
                            <PrimaryButton onClick={() => setModalOpen(true)} className="mt-4">
                                Add first month
                            </PrimaryButton>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="bg-muted/40 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3 text-left">Month</th>
                                        <th className="px-4 py-3 text-left">Balance</th>
                                        <th className="px-4 py-3 text-left">Credit</th>
                                        <th className="px-4 py-3 text-left">Debit</th>
                                        <th className="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-border">
                                    {monthYears.data.map((m) => {
                                        const credit = typeof m.credit === 'string' ? parseFloat(m.credit) : m.credit;
                                        const debit = typeof m.debit === 'string' ? parseFloat(m.debit) : m.debit;
                                        const bal = credit - debit;
                                        const isOpen = expanded.has(m.id);
                                        const walletRows = wallets[String(m.id)] ?? [];

                                        return (
                                            <Fragment key={m.id}>
                                                <tr className="group transition-colors hover:bg-muted/40">
                                                    <td className="px-4 py-3">
                                                        <button
                                                            type="button"
                                                            onClick={() => toggle(m.id)}
                                                            className="flex items-center gap-2 font-medium text-foreground transition-colors hover:text-primary"
                                                        >
                                                            {isOpen ? (
                                                                <MinusCircleIcon className="h-5 w-5 text-primary transition-transform" />
                                                            ) : (
                                                                <PlusCircleIcon className="h-5 w-5 text-primary transition-transform" />
                                                            )}
                                                            {m.month_year}
                                                        </button>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <Sensitive hidden={!numbersVisible}>
                                                            <span className={clsx('font-semibold', bal >= 0 ? 'text-success' : 'text-destructive')}>
                                                                E£ {fmt(bal)}
                                                            </span>
                                                        </Sensitive>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <Sensitive hidden={!numbersVisible}>
                                                            <span className="font-medium text-success">+E£ {fmt(credit)}</span>
                                                        </Sensitive>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <Sensitive hidden={!numbersVisible}>
                                                            <span className="font-medium text-destructive">-E£ {fmt(debit)}</span>
                                                        </Sensitive>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <div className="flex items-center justify-end gap-1">
                                                            <Link
                                                                href={`/month_years/${m.id}`}
                                                                className="inline-flex items-center rounded-full border border-border px-3 py-1 text-xs font-medium text-foreground transition-colors hover:border-primary hover:text-primary"
                                                            >
                                                                View Details
                                                            </Link>
                                                            <button
                                                                type="button"
                                                                onClick={() => setEditTarget(m)}
                                                                title="Edit month"
                                                                className="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-primary"
                                                            >
                                                                <PencilSquareIcon className="h-4 w-4" />
                                                            </button>
                                                            <button
                                                                type="button"
                                                                onClick={() => setDeleteTarget(m)}
                                                                title="Delete month"
                                                                className="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-destructive"
                                                            >
                                                                <TrashIcon className="h-4 w-4" />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                {isOpen && (
                                                    <tr className="bg-muted/30">
                                                        <td colSpan={5} className="px-4 py-3">
                                                            <div className="overflow-hidden rounded-md border border-border bg-card">
                                                                <table className="w-full text-xs">
                                                                    <thead className="text-muted-foreground">
                                                                        <tr className="border-b border-border">
                                                                            <th className="px-3 py-2 text-left font-medium">Wallet</th>
                                                                            <th className="px-3 py-2 text-left font-medium">Balance</th>
                                                                            <th className="px-3 py-2 text-left font-medium">Credit</th>
                                                                            <th className="px-3 py-2 text-left font-medium">Debit</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody className="divide-y divide-border">
                                                                        {walletRows.length === 0 && (
                                                                            <tr>
                                                                                <td colSpan={4} className="px-3 py-3 text-center text-muted-foreground">
                                                                                    No wallet data available
                                                                                </td>
                                                                            </tr>
                                                                        )}
                                                                        {walletRows.map((w, i) => {
                                                                            const wc = typeof w.credit === 'string' ? parseFloat(w.credit) : w.credit;
                                                                            const wd = typeof w.debit === 'string' ? parseFloat(w.debit) : w.debit;
                                                                            const wb = wc - wd;
                                                                            return (
                                                                                <tr key={`${m.id}-w-${w.wallet_id ?? i}`}>
                                                                                    <td className="px-3 py-2 text-foreground">{w.wallet_name ?? '—'}</td>
                                                                                    <td className="px-3 py-2">
                                                                                        <Sensitive hidden={!numbersVisible}>
                                                                                            <span className={clsx('font-semibold', wb >= 0 ? 'text-success' : 'text-destructive')}>
                                                                                                E£ {fmt(wb)}
                                                                                            </span>
                                                                                        </Sensitive>
                                                                                    </td>
                                                                                    <td className="px-3 py-2">
                                                                                        <Sensitive hidden={!numbersVisible}>
                                                                                            <span className="text-success">+E£ {fmt(wc)}</span>
                                                                                        </Sensitive>
                                                                                    </td>
                                                                                    <td className="px-3 py-2">
                                                                                        <Sensitive hidden={!numbersVisible}>
                                                                                            <span className="text-destructive">-E£ {fmt(wd)}</span>
                                                                                        </Sensitive>
                                                                                    </td>
                                                                                </tr>
                                                                            );
                                                                        })}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                )}
                                            </Fragment>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    )}

                    <div className="border-t border-border px-4 py-3">
                        <Pagination links={monthYears.links} />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

function Sensitive({ hidden, children }: PropsWithChildren<{ hidden: boolean }>) {
    return (
        <span
            aria-hidden={hidden ? 'true' : undefined}
            className={clsx(
                'inline-block transition-[filter] duration-150',
                hidden && 'select-none blur-md',
            )}
        >
            {children}
        </span>
    );
}

function SummaryCard({
    label,
    value,
    valueClass,
    icon: Icon,
    accent,
    badge,
    footnote,
    sensitive,
}: {
    label: string;
    value: string;
    valueClass: string;
    icon: typeof WalletIcon;
    accent: Accent;
    badge?: string;
    footnote?: string;
    sensitive: boolean;
}) {
    return (
        <div className="rounded-xl border border-border bg-card p-5 shadow-sm transition-colors hover:border-border/80">
            <div className="flex items-start justify-between">
                <div>
                    <p className="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{label}</p>
                    <p className={clsx('mt-1 font-heading text-2xl font-bold', valueClass)}>
                        <Sensitive hidden={sensitive}>{value}</Sensitive>
                    </p>
                </div>
                <div className={clsx('flex h-10 w-10 items-center justify-center rounded-full', ACCENT_CLASSES[accent])}>
                    <Icon className="h-5 w-5" />
                </div>
            </div>
            <div className="mt-3">
                {badge ? (
                    <span className={clsx('rounded-full px-2.5 py-1 text-xs font-medium', ACCENT_CLASSES[accent])}>{badge}</span>
                ) : (
                    <span className="text-xs text-muted-foreground">{footnote}</span>
                )}
            </div>
        </div>
    );
}
