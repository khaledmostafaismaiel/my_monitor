import { FormEventHandler, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { EyeIcon, EyeSlashIcon, WalletIcon, HomeIcon, UserGroupIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import GuestLayout from '@/Layouts/GuestLayout';
import FormField from '@/Components/FormField';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';

type Tab = 'sign_in' | 'sign_up';

export default function Login() {
    const [tab, setTab] = useState<Tab>('sign_in');
    const [showSignInPw, setShowSignInPw] = useState(false);
    const [showSignUpPw, setShowSignUpPw] = useState(false);
    const [showSignUpPw2, setShowSignUpPw2] = useState(false);

    const signIn = useForm({ user_name: '', password: '' });
    const signUp = useForm({
        first_name: '',
        last_name: '',
        email: '',
        family_option: 'create' as 'create' | 'join',
        family_name: '',
        family_id: '',
        password: '',
        password_confirmation: '',
        terms: false as boolean,
    });

    const submitSignIn: FormEventHandler = (e) => {
        e.preventDefault();
        signIn.post('/users/sign_in');
    };

    const submitSignUp: FormEventHandler = (e) => {
        e.preventDefault();
        signUp.post('/users/sign_up');
    };

    return (
        <GuestLayout>
            <Head title="Sign in" />

            <div className="rounded-2xl border border-border bg-card p-6 shadow-xl sm:p-8">
                <div className="mb-6 text-center">
                    <div className="mb-3 flex justify-center">
                        <div className="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-accent shadow-md">
                            <WalletIcon className="h-7 w-7 text-primary-foreground" />
                        </div>
                    </div>
                    <h1 className="font-heading text-2xl font-bold text-foreground">
                        {tab === 'sign_in' ? 'Welcome back' : 'Create your account'}
                    </h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        {tab === 'sign_in'
                            ? 'Sign in to continue to My Monitor'
                            : 'Set up your family workspace in seconds'}
                    </p>
                </div>

                <div className="mb-6 flex rounded-lg bg-muted p-1">
                    <button
                        type="button"
                        onClick={() => setTab('sign_in')}
                        className={clsx(
                            'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-colors duration-200',
                            tab === 'sign_in'
                                ? 'bg-card text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground',
                        )}
                    >
                        Sign In
                    </button>
                    <button
                        type="button"
                        onClick={() => setTab('sign_up')}
                        className={clsx(
                            'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-colors duration-200',
                            tab === 'sign_up'
                                ? 'bg-card text-foreground shadow-sm'
                                : 'text-muted-foreground hover:text-foreground',
                        )}
                    >
                        Sign Up
                    </button>
                </div>

                {tab === 'sign_in' ? (
                    <form onSubmit={submitSignIn} className="space-y-4">
                        <FormField label="Email address" htmlFor="user_name" error={signIn.errors.user_name ?? (signIn.errors as Record<string, string>).email}>
                            <TextInput
                                id="user_name"
                                type="email"
                                value={signIn.data.user_name}
                                onChange={(e) => signIn.setData('user_name', e.target.value)}
                                placeholder="name@example.com"
                                autoComplete="email"
                                required
                                isFocused
                            />
                        </FormField>

                        <FormField label="Password" htmlFor="signin_password" error={signIn.errors.password}>
                            <div className="relative">
                                <TextInput
                                    id="signin_password"
                                    type={showSignInPw ? 'text' : 'password'}
                                    className="pr-10"
                                    value={signIn.data.password}
                                    onChange={(e) => signIn.setData('password', e.target.value)}
                                    placeholder="Enter your password"
                                    autoComplete="current-password"
                                    required
                                />
                                <PasswordToggle visible={showSignInPw} onToggle={() => setShowSignInPw((v) => !v)} />
                            </div>
                        </FormField>

                        <PrimaryButton className="w-full" disabled={signIn.processing}>
                            {signIn.processing ? 'Signing in…' : 'Sign In'}
                        </PrimaryButton>
                    </form>
                ) : (
                    <form onSubmit={submitSignUp} className="space-y-4">
                        <div className="grid grid-cols-2 gap-3">
                            <FormField label="First name" htmlFor="first_name" error={signUp.errors.first_name}>
                                <TextInput
                                    id="first_name"
                                    value={signUp.data.first_name}
                                    onChange={(e) => signUp.setData('first_name', e.target.value)}
                                    autoComplete="given-name"
                                    required
                                />
                            </FormField>

                            <FormField label="Last name" htmlFor="last_name" error={signUp.errors.last_name}>
                                <TextInput
                                    id="last_name"
                                    value={signUp.data.last_name}
                                    onChange={(e) => signUp.setData('last_name', e.target.value)}
                                    autoComplete="family-name"
                                    required
                                />
                            </FormField>
                        </div>

                        <FormField label="Email address" htmlFor="email" error={signUp.errors.email}>
                            <TextInput
                                id="email"
                                type="email"
                                value={signUp.data.email}
                                onChange={(e) => signUp.setData('email', e.target.value)}
                                autoComplete="email"
                                required
                            />
                        </FormField>

                        <FormField label="Family setup" error={signUp.errors.family_option}>
                            <div className="grid grid-cols-2 gap-3">
                                <FamilyOptionCard
                                    selected={signUp.data.family_option === 'create'}
                                    onClick={() => signUp.setData('family_option', 'create')}
                                    icon={HomeIcon}
                                    label="Create New"
                                />
                                <FamilyOptionCard
                                    selected={signUp.data.family_option === 'join'}
                                    onClick={() => signUp.setData('family_option', 'join')}
                                    icon={UserGroupIcon}
                                    label="Join Existing"
                                />
                            </div>
                        </FormField>

                        {signUp.data.family_option === 'create' && (
                            <FormField label="Family name" htmlFor="family_name" error={signUp.errors.family_name}>
                                <TextInput
                                    id="family_name"
                                    value={signUp.data.family_name}
                                    onChange={(e) => signUp.setData('family_name', e.target.value)}
                                    placeholder="e.g. The Smiths"
                                />
                            </FormField>
                        )}

                        {signUp.data.family_option === 'join' && (
                            <FormField label="Family ID" htmlFor="family_id" error={signUp.errors.family_id}>
                                <TextInput
                                    id="family_id"
                                    value={signUp.data.family_id}
                                    onChange={(e) => signUp.setData('family_id', e.target.value)}
                                    placeholder="Enter Family ID"
                                />
                            </FormField>
                        )}

                        <FormField label="Password" htmlFor="signup_password" error={signUp.errors.password}>
                            <div className="relative">
                                <TextInput
                                    id="signup_password"
                                    type={showSignUpPw ? 'text' : 'password'}
                                    className="pr-10"
                                    value={signUp.data.password}
                                    onChange={(e) => signUp.setData('password', e.target.value)}
                                    autoComplete="new-password"
                                    required
                                />
                                <PasswordToggle visible={showSignUpPw} onToggle={() => setShowSignUpPw((v) => !v)} />
                            </div>
                        </FormField>

                        <FormField label="Confirm password" htmlFor="password_confirmation" error={signUp.errors.password_confirmation}>
                            <div className="relative">
                                <TextInput
                                    id="password_confirmation"
                                    type={showSignUpPw2 ? 'text' : 'password'}
                                    className="pr-10"
                                    value={signUp.data.password_confirmation}
                                    onChange={(e) => signUp.setData('password_confirmation', e.target.value)}
                                    autoComplete="new-password"
                                    required
                                />
                                <PasswordToggle visible={showSignUpPw2} onToggle={() => setShowSignUpPw2((v) => !v)} />
                            </div>
                        </FormField>

                        <label className="flex items-start gap-2 text-xs text-muted-foreground">
                            <input
                                type="checkbox"
                                className="mt-0.5 rounded border-input bg-card text-primary focus:ring-ring"
                                checked={signUp.data.terms}
                                onChange={(e) => signUp.setData('terms', e.target.checked)}
                                required
                            />
                            <span>
                                I agree to the{' '}
                                <Link href="#" className="text-primary hover:underline">
                                    Terms of Service
                                </Link>{' '}
                                and{' '}
                                <Link href="#" className="text-primary hover:underline">
                                    Privacy Policy
                                </Link>
                                .
                            </span>
                        </label>
                        {signUp.errors.terms && (
                            <p className="text-xs text-destructive">{signUp.errors.terms}</p>
                        )}

                        <PrimaryButton className="w-full" disabled={signUp.processing}>
                            {signUp.processing ? 'Creating account…' : 'Create account'}
                        </PrimaryButton>
                    </form>
                )}
            </div>

            <p className="mt-6 text-center text-xs text-muted-foreground">
                &copy; {new Date().getFullYear()} My Monitor. All rights reserved.
            </p>
        </GuestLayout>
    );
}

function PasswordToggle({ visible, onToggle }: { visible: boolean; onToggle: () => void }) {
    return (
        <button
            type="button"
            onClick={onToggle}
            className="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-muted-foreground transition-colors hover:text-foreground"
            aria-label={visible ? 'Hide password' : 'Show password'}
        >
            {visible ? <EyeSlashIcon className="h-4 w-4" /> : <EyeIcon className="h-4 w-4" />}
        </button>
    );
}

function FamilyOptionCard({
    selected,
    onClick,
    icon: Icon,
    label,
}: {
    selected: boolean;
    onClick: () => void;
    icon: typeof HomeIcon;
    label: string;
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            className={clsx(
                'flex flex-col items-center gap-1 rounded-lg border p-3 text-sm transition-all duration-150',
                selected
                    ? 'border-primary bg-primary/10 text-primary shadow-sm'
                    : 'border-border text-muted-foreground hover:border-primary/40 hover:bg-muted',
            )}
        >
            <Icon className="h-6 w-6" />
            <span className="font-medium">{label}</span>
        </button>
    );
}
