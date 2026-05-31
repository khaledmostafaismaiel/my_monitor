import { FormEventHandler, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import Modal from './Modal';
import FormField from './FormField';
import TextInput from './TextInput';
import PrimaryButton from './PrimaryButton';
import SecondaryButton from './SecondaryButton';

type EditTarget = { id: number; year: number; month: number };

export default function MonthYearFormModal({
    show,
    onClose,
    monthYear,
}: {
    show: boolean;
    onClose: () => void;
    monthYear?: EditTarget | null;
}) {
    const isEdit = !!monthYear;

    const today = new Date();
    const currentMonth = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
    const initial = monthYear
        ? `${monthYear.year}-${String(monthYear.month).padStart(2, '0')}`
        : currentMonth;

    const form = useForm({ month_year: initial });

    useEffect(() => {
        if (show) {
            form.setData('month_year', initial);
            form.clearErrors();
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [show, monthYear?.id]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const opts = { onSuccess: () => onClose(), preserveScroll: true };
        if (isEdit && monthYear) {
            form.put(`/month_years/${monthYear.id}`, opts);
        } else {
            form.post('/month_years', opts);
        }
    };

    return (
        <Modal show={show} onClose={onClose} maxWidth="md">
            <form onSubmit={submit} className="p-6">
                <h3 className="text-lg font-semibold text-foreground">
                    {isEdit ? 'Edit month' : 'Add a month'}
                </h3>
                <p className="mt-1 text-sm text-muted-foreground">
                    {isEdit ? 'Change the month/year for this entry.' : 'Pick the month you want to start tracking.'}
                </p>

                <div className="mt-4">
                    <FormField label="Month" htmlFor="month_year" error={form.errors.month_year}>
                        <TextInput
                            id="month_year"
                            type="month"
                            className="block w-full"
                            value={form.data.month_year}
                            onChange={(e) => form.setData('month_year', e.target.value)}
                            required
                        />
                    </FormField>
                </div>

                <div className="mt-6 flex justify-end gap-3">
                    <SecondaryButton type="button" onClick={onClose} disabled={form.processing}>
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton disabled={form.processing}>
                        {form.processing ? 'Saving…' : isEdit ? 'Save changes' : 'Add month'}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    );
}
