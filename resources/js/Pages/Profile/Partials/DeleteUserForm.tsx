import DangerButton from '@/Components/DangerButton';
import FormField from '@/Components/FormField';
import Modal from '@/Components/Modal';
import SecondaryButton from '@/Components/SecondaryButton';
import TextInput from '@/Components/TextInput';
import { useForm } from '@inertiajs/react';
import { ExclamationTriangleIcon } from '@heroicons/react/24/outline';
import { FormEventHandler, useRef, useState } from 'react';

export default function DeleteUserForm({ className = '' }: { className?: string }) {
    const [confirming, setConfirming] = useState(false);
    const passwordInput = useRef<HTMLInputElement>(null);

    const {
        data,
        setData,
        delete: destroy,
        processing,
        reset,
        errors,
        clearErrors,
    } = useForm({ password: '' });

    const closeModal = () => {
        setConfirming(false);
        clearErrors();
        reset();
    };

    const deleteUser: FormEventHandler = (e) => {
        e.preventDefault();
        destroy(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.current?.focus(),
            onFinish: () => reset(),
        });
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-foreground">Delete Account</h2>
                <p className="mt-1 text-sm text-muted-foreground">
                    Permanently delete your account. Your transactions stay with your family;
                    your personal todos and OTPs are removed. <span className="font-medium text-destructive">This action can't be undone.</span>
                </p>
            </header>

            <div className="mt-4">
                <DangerButton onClick={() => setConfirming(true)}>Delete Account</DangerButton>
            </div>

            <Modal show={confirming} onClose={closeModal} maxWidth="md">
                <form onSubmit={deleteUser} className="p-6">
                    <div className="flex gap-4">
                        <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-destructive/15 text-destructive">
                            <ExclamationTriangleIcon className="h-6 w-6" />
                        </div>
                        <div className="flex-1">
                            <h3 className="text-lg font-semibold text-foreground">
                                Delete your account?
                            </h3>
                            <p className="mt-2 text-sm text-muted-foreground">
                                Once deleted, you'll be signed out and the account can't be recovered.
                                Enter your current password to confirm.
                            </p>
                        </div>
                    </div>

                    <div className="mt-6">
                        <FormField label="Current password" htmlFor="delete_password" error={errors.password}>
                            <TextInput
                                id="delete_password"
                                type="password"
                                name="password"
                                ref={passwordInput}
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                autoComplete="current-password"
                                required
                                isFocused
                                placeholder="Your password"
                            />
                        </FormField>
                    </div>

                    <div className="mt-6 flex justify-end gap-3">
                        <SecondaryButton type="button" onClick={closeModal} disabled={processing}>
                            Cancel
                        </SecondaryButton>
                        <DangerButton disabled={processing}>
                            {processing ? 'Deleting…' : 'Delete account'}
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </section>
    );
}
