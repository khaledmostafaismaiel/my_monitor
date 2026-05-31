import Modal from './Modal';
import DangerButton from './DangerButton';
import SecondaryButton from './SecondaryButton';
import { ExclamationTriangleIcon } from '@heroicons/react/24/outline';

export default function ConfirmModal({
    show,
    title,
    message,
    confirmLabel = 'Delete',
    cancelLabel = 'Cancel',
    processing = false,
    onConfirm,
    onClose,
}: {
    show: boolean;
    title: string;
    message: string;
    confirmLabel?: string;
    cancelLabel?: string;
    processing?: boolean;
    onConfirm: () => void;
    onClose: () => void;
}) {
    return (
        <Modal show={show} onClose={onClose} maxWidth="md">
            <div className="p-6">
                <div className="flex gap-4">
                    <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-destructive/15 text-destructive">
                        <ExclamationTriangleIcon className="h-6 w-6" />
                    </div>
                    <div className="flex-1">
                        <h3 className="text-lg font-semibold text-foreground">{title}</h3>
                        <p className="mt-2 text-sm text-muted-foreground">{message}</p>
                    </div>
                </div>

                <div className="mt-6 flex justify-end gap-3">
                    <SecondaryButton onClick={onClose} disabled={processing}>
                        {cancelLabel}
                    </SecondaryButton>
                    <DangerButton onClick={onConfirm} disabled={processing}>
                        {processing ? 'Working…' : confirmLabel}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    );
}
