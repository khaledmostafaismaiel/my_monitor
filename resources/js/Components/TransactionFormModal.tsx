import { FormEventHandler, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import Modal from './Modal';
import FormField from './FormField';
import TextInput from './TextInput';
import Select from './Select';
import PrimaryButton from './PrimaryButton';
import SecondaryButton from './SecondaryButton';
import clsx from 'clsx';
import { MonthYear, Transaction, Wallet } from '@/types';

type CategoryOption = { id: number; name: string };
type WalletOption = Pick<Wallet, 'id' | 'name'>;
type MonthYearOption = Pick<MonthYear, 'id' | 'month' | 'year'>;

export type TransactionScope = 'normal' | 'draft' | 'blueprint';

type Props = {
    show: boolean;
    onClose: () => void;
    scope: TransactionScope;
    mode: 'create' | 'edit';
    transaction?: Partial<Transaction>;
    endpoint: string;
    method?: 'post' | 'put';
    categories: CategoryOption[];
    wallets?: WalletOption[];
    monthYears?: MonthYearOption[];
    title?: string;
    extraData?: Record<string, unknown>;
};

const monthLabel = (m: MonthYearOption) =>
    `${m.year}-${String(m.month).padStart(2, '0')}`;

export default function TransactionFormModal({
    show,
    onClose,
    scope,
    mode,
    transaction,
    endpoint,
    method = mode === 'edit' ? 'put' : 'post',
    categories,
    wallets,
    monthYears,
    title,
    extraData,
}: Props) {
    const includeWalletAndMonth = scope !== 'blueprint';

    const form = useForm({
        name: transaction?.name ?? '',
        price: transaction?.price?.toString() ?? '',
        quantity: transaction?.quantity?.toString() ?? '1',
        direction: (transaction?.direction ?? 'debit') as 'credit' | 'debit',
        category_id: transaction?.category_id?.toString() ?? '',
        wallet_id: transaction?.wallet_id?.toString() ?? '',
        month_year_id: transaction?.month_year_id?.toString() ?? '',
        date: transaction?.date ?? new Date().toISOString().slice(0, 10),
        comment: transaction?.comment ?? '',
    });

    useEffect(() => {
        if (show) {
            form.setData({
                name: transaction?.name ?? '',
                price: transaction?.price?.toString() ?? '',
                quantity: transaction?.quantity?.toString() ?? '1',
                direction: (transaction?.direction ?? 'debit') as 'credit' | 'debit',
                category_id: transaction?.category_id?.toString() ?? '',
                wallet_id: transaction?.wallet_id?.toString() ?? '',
                month_year_id: transaction?.month_year_id?.toString() ?? '',
                date: transaction?.date ?? new Date().toISOString().slice(0, 10),
                comment: transaction?.comment ?? '',
            });
            form.clearErrors();
        }
    }, [show, transaction?.id]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const opts = { onSuccess: () => onClose(), preserveScroll: true };
        if (extraData) form.transform((d) => ({ ...d, ...extraData }));
        if (method === 'put') form.put(endpoint, opts);
        else form.post(endpoint, opts);
    };

    return (
        <Modal show={show} onClose={onClose} maxWidth="lg">
            <form onSubmit={submit} className="p-6">
                <h3 className="text-lg font-semibold text-foreground">
                    {title ?? (mode === 'edit' ? 'Edit transaction' : 'New transaction')}
                </h3>

                <div className="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div className="sm:col-span-2">
                        <FormField label="Name" htmlFor="tx_name" error={form.errors.name}>
                            <TextInput
                                id="tx_name"
                                className="block w-full"
                                value={form.data.name}
                                onChange={(e) => form.setData('name', e.target.value)}
                                required
                                isFocused
                            />
                        </FormField>
                    </div>

                    <FormField label="Price" htmlFor="tx_price" error={form.errors.price}>
                        <TextInput
                            id="tx_price"
                            type="number"
                            step="0.01"
                            min="0"
                            className="block w-full"
                            value={form.data.price}
                            onChange={(e) => form.setData('price', e.target.value)}
                            required
                        />
                    </FormField>

                    <FormField label="Quantity" htmlFor="tx_qty" error={form.errors.quantity}>
                        <TextInput
                            id="tx_qty"
                            type="number"
                            min="1"
                            className="block w-full"
                            value={form.data.quantity}
                            onChange={(e) => form.setData('quantity', e.target.value)}
                            required
                        />
                    </FormField>

                    <div className="sm:col-span-2">
                        <FormField label="Direction" error={form.errors.direction}>
                            <div className="flex gap-2">
                                <DirectionPill
                                    selected={form.data.direction === 'debit'}
                                    onClick={() => form.setData('direction', 'debit')}
                                    label="Debit (expense)"
                                    color="red"
                                />
                                <DirectionPill
                                    selected={form.data.direction === 'credit'}
                                    onClick={() => form.setData('direction', 'credit')}
                                    label="Credit (income)"
                                    color="emerald"
                                />
                            </div>
                        </FormField>
                    </div>

                    <div className={includeWalletAndMonth ? '' : 'sm:col-span-2'}>
                        <FormField label="Category" htmlFor="tx_cat" error={form.errors.category_id}>
                            <Select
                                id="tx_cat"
                                className="block w-full"
                                value={form.data.category_id}
                                onChange={(e) => form.setData('category_id', e.target.value)}
                                placeholder="Select a category"
                                options={categories.map((c) => ({ value: c.id, label: c.name }))}
                                required
                            />
                        </FormField>
                    </div>

                    {includeWalletAndMonth && (
                        <>
                            <FormField label="Wallet" htmlFor="tx_wallet" error={form.errors.wallet_id}>
                                <Select
                                    id="tx_wallet"
                                    className="block w-full"
                                    value={form.data.wallet_id}
                                    onChange={(e) => form.setData('wallet_id', e.target.value)}
                                    placeholder="Select a wallet"
                                    options={(wallets ?? []).map((w) => ({ value: w.id, label: w.name }))}
                                    required
                                />
                            </FormField>

                            <FormField label="Month" htmlFor="tx_month" error={form.errors.month_year_id}>
                                <Select
                                    id="tx_month"
                                    className="block w-full"
                                    value={form.data.month_year_id}
                                    onChange={(e) => form.setData('month_year_id', e.target.value)}
                                    placeholder="Select month"
                                    options={(monthYears ?? []).map((m) => ({ value: m.id, label: monthLabel(m) }))}
                                    required
                                />
                            </FormField>

                            <FormField label="Date" htmlFor="tx_date" error={form.errors.date}>
                                <TextInput
                                    id="tx_date"
                                    type="date"
                                    className="block w-full"
                                    value={form.data.date}
                                    onChange={(e) => form.setData('date', e.target.value)}
                                    required
                                />
                            </FormField>
                        </>
                    )}

                    <div className="sm:col-span-2">
                        <FormField label="Comment" htmlFor="tx_comment" error={form.errors.comment}>
                            <textarea
                                id="tx_comment"
                                rows={2}
                                className="block w-full rounded-md border-input bg-card text-foreground shadow-sm placeholder:text-muted-foreground focus:border-ring focus:ring-ring"
                                value={form.data.comment ?? ''}
                                onChange={(e) => form.setData('comment', e.target.value)}
                            />
                        </FormField>
                    </div>
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

function DirectionPill({
    selected,
    onClick,
    label,
    color,
}: {
    selected: boolean;
    onClick: () => void;
    label: string;
    color: 'red' | 'emerald';
}) {
    const palette =
        color === 'red'
            ? selected
                ? 'border-destructive bg-destructive/10 text-destructive'
                : 'border-border text-muted-foreground hover:border-destructive/40 hover:bg-destructive/5'
            : selected
              ? 'border-success bg-success/10 text-success'
              : 'border-border text-muted-foreground hover:border-success/40 hover:bg-success/5';
    return (
        <button
            type="button"
            onClick={onClick}
            className={clsx('flex-1 rounded-md border px-3 py-2 text-sm font-medium transition', palette)}
        >
            {label}
        </button>
    );
}
