import { FormEventHandler, useEffect, useState } from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import { PlusIcon, MagnifyingGlassIcon, PencilSquareIcon, TrashIcon, WalletIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PageHeader from '@/Components/PageHeader';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import FormField from '@/Components/FormField';
import Select from '@/Components/Select';
import Modal from '@/Components/Modal';
import ConfirmModal from '@/Components/ConfirmModal';
import Pagination from '@/Components/Pagination';
import { Paginator, Wallet } from '@/types';

type WalletRow = Wallet & { status?: 'active' | 'inactive'; created_at?: string };

type Props = {
    wallets: Paginator<WalletRow>;
    filters: { name?: string; status?: string };
};

export default function WalletsIndex({ wallets, filters }: Props) {
    const [filtersOpen, setFiltersOpen] = useState(!!(filters.name || filters.status));
    const [formOpen, setFormOpen] = useState<{ mode: 'create' } | { mode: 'edit'; wallet: WalletRow } | null>(null);
    const [deleteTarget, setDeleteTarget] = useState<WalletRow | null>(null);

    const [name, setName] = useState(filters.name ?? '');
    const [status, setStatus] = useState(filters.status ?? '');

    const applyFilters = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/wallets', { name, status }, { preserveState: true, preserveScroll: true });
    };

    const clearFilters = () => {
        setName('');
        setStatus('');
        router.get('/wallets', {}, { preserveState: true, preserveScroll: true });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Wallets" />

            <PageHeader
                title="Wallets"
                description="Manage your payment methods"
                actions={
                    <>
                        <SecondaryButton onClick={() => setFiltersOpen((v) => !v)}>
                            <MagnifyingGlassIcon className="mr-1.5 h-4 w-4" /> Search
                        </SecondaryButton>
                        <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                            <PlusIcon className="mr-1.5 h-4 w-4" /> Add Wallet
                        </PrimaryButton>
                    </>
                }
            />

            {filtersOpen && (
                <form onSubmit={applyFilters} className="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-border bg-card p-4 sm:grid-cols-4">
                    <div className="sm:col-span-2">
                        <FormField label="Search">
                            <TextInput value={name} onChange={(e) => setName(e.target.value)} placeholder="Wallet name…" />
                        </FormField>
                    </div>
                    <FormField label="Status">
                        <Select
                            value={status}
                            onChange={(e) => setStatus(e.target.value)}
                            placeholder="All"
                            options={[
                                { value: 'active', label: 'Active' },
                                { value: 'inactive', label: 'Inactive' },
                            ]}
                        />
                    </FormField>
                    <div className="flex items-end gap-2">
                        <PrimaryButton>Apply</PrimaryButton>
                        <button type="button" onClick={clearFilters} className="text-sm text-muted-foreground transition-colors hover:text-foreground">
                            Clear
                        </button>
                    </div>
                </form>
            )}

            <div className="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/40 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            <tr>
                                <th className="px-4 py-3 text-left">Name</th>
                                <th className="px-4 py-3 text-left">Status</th>
                                <th className="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-border">
                            {wallets.data.length === 0 ? (
                                <tr>
                                    <td colSpan={3} className="px-4 py-16 text-center">
                                        <div className="mx-auto flex max-w-sm flex-col items-center gap-3">
                                            <div className="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                                                <WalletIcon className="h-8 w-8 text-muted-foreground" />
                                            </div>
                                            <h3 className="text-base font-semibold text-foreground">No wallets yet</h3>
                                            <p className="text-sm text-muted-foreground">Add your first wallet to start tracking spend.</p>
                                            <PrimaryButton onClick={() => setFormOpen({ mode: 'create' })}>
                                                <PlusIcon className="mr-1.5 h-4 w-4" /> Add Wallet
                                            </PrimaryButton>
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                wallets.data.map((w) => (
                                    <tr key={w.id} className="group transition-colors even:bg-muted/30 hover:bg-muted/50">
                                        <td className="px-4 py-3">
                                            <div className="flex items-center gap-2">
                                                <WalletIcon className="h-5 w-5 text-primary" />
                                                <span className="font-medium text-foreground">{w.name}</span>
                                            </div>
                                        </td>
                                        <td className="px-4 py-3">
                                            <span className={clsx(
                                                'rounded-full px-2.5 py-1 text-xs font-medium capitalize',
                                                w.status === 'active' ? 'bg-success/15 text-success' : 'bg-muted text-muted-foreground',
                                            )}>
                                                {w.status ?? 'active'}
                                            </span>
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex justify-end gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                                <IconBtn onClick={() => setFormOpen({ mode: 'edit', wallet: w })} title="Edit">
                                                    <PencilSquareIcon className="h-4 w-4" />
                                                </IconBtn>
                                                <IconBtn onClick={() => setDeleteTarget(w)} title="Delete" danger>
                                                    <TrashIcon className="h-4 w-4" />
                                                </IconBtn>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
                <div className="border-t border-border px-4 py-3">
                    <Pagination links={wallets.links} />
                </div>
            </div>

            <WalletFormModal
                show={!!formOpen}
                mode={formOpen?.mode ?? 'create'}
                wallet={formOpen?.mode === 'edit' ? formOpen.wallet : undefined}
                onClose={() => setFormOpen(null)}
            />

            <ConfirmModal
                show={!!deleteTarget}
                title="Delete wallet?"
                message={deleteTarget ? `Are you sure you want to delete "${deleteTarget.name}"? This can't be undone.` : ''}
                onClose={() => setDeleteTarget(null)}
                onConfirm={() => {
                    if (!deleteTarget) return;
                    router.delete(`/wallets/${deleteTarget.id}`, {
                        onSuccess: () => setDeleteTarget(null),
                        preserveScroll: true,
                    });
                }}
            />
        </AuthenticatedLayout>
    );
}

function IconBtn({ children, onClick, title, danger }: { children: React.ReactNode; onClick: () => void; title: string; danger?: boolean }) {
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

function WalletFormModal({
    show, mode, wallet, onClose,
}: {
    show: boolean;
    mode: 'create' | 'edit';
    wallet?: WalletRow;
    onClose: () => void;
}) {
    const form = useForm({
        name: wallet?.name ?? '',
        status: (wallet?.status ?? 'active') as 'active' | 'inactive',
    });

    useEffect(() => {
        if (show) {
            form.setData({
                name: wallet?.name ?? '',
                status: (wallet?.status ?? 'active') as 'active' | 'inactive',
            });
            form.clearErrors();
        }
    }, [show, wallet?.id]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const opts = { onSuccess: () => onClose(), preserveScroll: true };
        if (mode === 'edit' && wallet) form.put(`/wallets/${wallet.id}`, opts);
        else form.post('/wallets', opts);
    };

    return (
        <Modal show={show} onClose={onClose} maxWidth="md">
            <form onSubmit={submit} className="p-6">
                <h3 className="text-lg font-semibold text-foreground">
                    {mode === 'edit' ? 'Edit wallet' : 'New wallet'}
                </h3>

                <div className="mt-4 space-y-4">
                    <FormField label="Name" htmlFor="wallet_name" error={form.errors.name}>
                        <TextInput
                            id="wallet_name"
                            value={form.data.name}
                            onChange={(e) => form.setData('name', e.target.value)}
                            placeholder="Enter wallet name…"
                            required
                            isFocused
                        />
                    </FormField>

                    <FormField label="Status" htmlFor="wallet_status" error={form.errors.status}>
                        <Select
                            id="wallet_status"
                            value={form.data.status}
                            onChange={(e) => form.setData('status', e.target.value as 'active' | 'inactive')}
                            options={[
                                { value: 'active', label: 'Active' },
                                { value: 'inactive', label: 'Inactive' },
                            ]}
                        />
                    </FormField>
                </div>

                <div className="mt-6 flex justify-end gap-3">
                    <SecondaryButton type="button" onClick={onClose} disabled={form.processing}>Cancel</SecondaryButton>
                    <PrimaryButton disabled={form.processing}>
                        {form.processing ? 'Saving…' : 'Save'}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    );
}
