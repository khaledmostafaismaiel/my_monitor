import { FormEventHandler, useRef, useState, useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { ShieldCheckIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import GuestLayout from '@/Layouts/GuestLayout';
import PrimaryButton from '@/Components/PrimaryButton';

export default function VerifyOtp({ email }: { email?: string }) {
    const [digits, setDigits] = useState<string[]>(['', '', '', '', '', '']);
    const [anyFocused, setAnyFocused] = useState(false);
    const refs = useRef<Array<HTMLInputElement | null>>([]);

    const form = useForm({
        otp1: '', otp2: '', otp3: '', otp4: '', otp5: '', otp6: '',
    });

    useEffect(() => {
        refs.current[0]?.focus();
    }, []);

    const handleChange = (i: number, value: string) => {
        const v = value.replace(/[^0-9]/g, '').slice(-1);
        const next = [...digits];
        next[i] = v;
        setDigits(next);
        form.setData(`otp${i + 1}` as keyof typeof form.data, v);
        if (v && i < 5) refs.current[i + 1]?.focus();
    };

    const handleKeyDown = (i: number, e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Backspace' && !digits[i] && i > 0) refs.current[i - 1]?.focus();
    };

    const handlePaste = (e: React.ClipboardEvent<HTMLInputElement>) => {
        const pasted = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
        if (pasted.length === 0) return;
        e.preventDefault();
        const next = ['', '', '', '', '', ''];
        for (let i = 0; i < pasted.length; i++) {
            next[i] = pasted[i];
            form.setData(`otp${i + 1}` as keyof typeof form.data, pasted[i]);
        }
        setDigits(next);
        refs.current[Math.min(pasted.length, 5)]?.focus();
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        form.post('/users/verify_otp');
    };

    const allFilled = digits.every((d) => d !== '');

    return (
        <GuestLayout>
            <Head title="Verify your email" />

            <div className="rounded-2xl border border-border bg-card p-6 shadow-xl sm:p-8">
                <div className="mb-6 text-center">
                    <div className="mb-3 flex justify-center">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-accent shadow-md">
                            <ShieldCheckIcon className="h-7 w-7 text-primary-foreground" />
                        </div>
                    </div>
                    <h1 className="font-heading text-2xl font-bold text-foreground">Verify your email</h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        We sent a 6-digit code to{' '}
                        <span className="font-medium text-foreground">{email ?? 'your email address'}</span>.
                    </p>
                </div>

                <form onSubmit={submit} className="space-y-5">
                    <div
                        className={clsx(
                            'flex justify-between gap-2 rounded-lg p-1 transition-all',
                            anyFocused && 'ring-2 ring-ring/40',
                        )}
                        onPaste={handlePaste}
                    >
                        {digits.map((d, i) => (
                            <input
                                key={i}
                                ref={(el) => { refs.current[i] = el; }}
                                inputMode="numeric"
                                pattern="[0-9]*"
                                maxLength={1}
                                value={d}
                                onChange={(e) => handleChange(i, e.target.value)}
                                onKeyDown={(e) => handleKeyDown(i, e)}
                                onFocus={() => setAnyFocused(true)}
                                onBlur={() => setAnyFocused(false)}
                                className="h-14 w-12 rounded-md border border-input bg-card text-center text-lg font-semibold text-foreground shadow-sm transition-colors focus:border-ring focus:ring-ring"
                                required
                            />
                        ))}
                    </div>

                    {form.errors.otp1 && (
                        <p className="text-center text-sm text-destructive">{form.errors.otp1}</p>
                    )}

                    <PrimaryButton className="w-full" disabled={!allFilled || form.processing}>
                        {form.processing ? 'Verifying…' : 'Verify'}
                    </PrimaryButton>
                </form>
            </div>
        </GuestLayout>
    );
}
