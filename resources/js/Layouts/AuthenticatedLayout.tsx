import { Link, router, usePage } from '@inertiajs/react';
import { Dialog, DialogPanel, Transition, TransitionChild } from '@headlessui/react';
import {
    Bars3Icon,
    XMarkIcon,
    HomeIcon,
    ArrowsRightLeftIcon,
    PencilSquareIcon,
    RectangleStackIcon,
    TagIcon,
    WalletIcon,
    CheckCircleIcon,
    ArrowLeftStartOnRectangleIcon,
    UserIcon,
    ClipboardDocumentIcon,
    PresentationChartLineIcon,
} from '@heroicons/react/24/outline';
import { Fragment, PropsWithChildren, ReactNode, useState } from 'react';
import clsx from 'clsx';
import FlashBanner from '@/Components/FlashBanner';
import ThemeToggle from '@/Components/ThemeToggle';

type NavItem = {
    label: string;
    href: string;
    icon: typeof HomeIcon;
    matches: (url: string) => boolean;
};

const NAV: NavItem[] = [
    { label: 'Dashboard', href: '/', icon: HomeIcon, matches: (u) => u === '/' },
    { label: 'Normal Transactions', href: '/normal_transactions', icon: ArrowsRightLeftIcon, matches: (u) => u.startsWith('/normal_transactions') },
    { label: 'Draft Transactions', href: '/draft_transactions', icon: PencilSquareIcon, matches: (u) => u.startsWith('/draft_transactions') },
    { label: 'Blueprint Transactions', href: '/blueprint_transactions', icon: RectangleStackIcon, matches: (u) => u.startsWith('/blueprint_transactions') },
    { label: 'Categories', href: '/categories', icon: TagIcon, matches: (u) => u.startsWith('/categories') },
    { label: 'Wallets', href: '/wallets', icon: WalletIcon, matches: (u) => u.startsWith('/wallets') },
    { label: 'Todos', href: '/todos', icon: CheckCircleIcon, matches: (u) => u.startsWith('/todos') },
];

export default function AuthenticatedLayout({
    header,
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    const { auth } = usePage().props as unknown as {
        auth: { user: { first_name: string; last_name: string; email: string } | null; family: { id: number; name: string } | null };
    };
    const currentUrl = usePage().url;

    const [mobileOpen, setMobileOpen] = useState(false);
    const [copied, setCopied] = useState(false);

    const user = auth.user;
    const family = auth.family;

    const copyFamilyId = () => {
        if (!family) return;
        navigator.clipboard.writeText(String(family.id)).then(() => {
            setCopied(true);
            setTimeout(() => setCopied(false), 1500);
        });
    };

    const signOut = () => router.post('/users/sign_out');

    const sidebarContent = (
        <div className="flex h-full flex-col bg-card">
            <div className="flex items-center gap-3 border-b border-border px-6 py-5">
                <Link
                    href="/"
                    onClick={() => setMobileOpen(false)}
                    aria-label="Go to dashboard"
                    className="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-primary-foreground shadow-md transition-opacity hover:opacity-80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-card"
                >
                    <PresentationChartLineIcon className="h-6 w-6" />
                </Link>
                <div className="min-w-0">
                    <Link
                        href="/"
                        onClick={() => setMobileOpen(false)}
                        className="block truncate rounded font-heading text-lg font-semibold text-foreground transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-card"
                    >
                        My Monitor
                    </Link>
                    <div className="truncate text-xs text-muted-foreground">{family?.name ?? 'Personal'}</div>
                </div>
            </div>

            <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                {NAV.map((item) => {
                    const active = item.matches(currentUrl);
                    const Icon = item.icon;
                    return (
                        <Link
                            key={item.href}
                            href={item.href}
                            onClick={() => setMobileOpen(false)}
                            className={clsx(
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                active
                                    ? 'bg-primary text-primary-foreground shadow-sm'
                                    : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                            )}
                        >
                            <Icon className="h-5 w-5" />
                            {item.label}
                        </Link>
                    );
                })}
            </nav>

            <div className="space-y-2 border-t border-border px-4 py-4">
                {user && (
                    <Link
                        href="/profile"
                        onClick={() => setMobileOpen(false)}
                        className="flex items-center gap-3 rounded-md px-1 py-1.5 transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-card"
                        title="View profile"
                    >
                        <div className="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-primary to-accent text-sm font-bold text-primary-foreground">
                            {user.first_name?.[0]?.toUpperCase() ?? <UserIcon className="h-4 w-4" />}
                        </div>
                        <div className="min-w-0 flex-1">
                            <div className="truncate text-sm font-medium text-foreground">
                                {user.first_name} {user.last_name}
                            </div>
                            <div className="truncate text-xs text-muted-foreground">{user.email}</div>
                        </div>
                    </Link>
                )}

                {family && (
                    <button
                        type="button"
                        onClick={copyFamilyId}
                        className="flex w-full items-center justify-between rounded-md border border-border bg-muted/50 px-3 py-2 text-xs text-muted-foreground transition-colors hover:border-primary hover:text-foreground"
                    >
                        <span className="font-mono">Family #{family.id}</span>
                        <span className={clsx('flex items-center gap-1', copied && 'text-success')}>
                            <ClipboardDocumentIcon className="h-4 w-4" />
                            {copied ? 'Copied' : 'Copy'}
                        </span>
                    </button>
                )}

                <ThemeToggle />

                <button
                    type="button"
                    onClick={signOut}
                    className="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-muted hover:text-destructive"
                >
                    <ArrowLeftStartOnRectangleIcon className="h-4 w-4" />
                    Sign out
                </button>
            </div>
        </div>
    );

    return (
        <div className="min-h-screen bg-background">
            <FlashBanner />

            <Transition show={mobileOpen} as={Fragment}>
                <Dialog as="div" className="relative z-50 md:hidden" onClose={setMobileOpen}>
                    <TransitionChild
                        as={Fragment}
                        enter="transition-opacity ease-linear duration-200"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="transition-opacity ease-linear duration-150"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <div className="fixed inset-0 bg-foreground/40 backdrop-blur-sm" />
                    </TransitionChild>

                    <div className="fixed inset-0 flex">
                        <TransitionChild
                            as={Fragment}
                            enter="transition ease-in-out duration-200 transform"
                            enterFrom="-translate-x-full"
                            enterTo="translate-x-0"
                            leave="transition ease-in-out duration-150 transform"
                            leaveFrom="translate-x-0"
                            leaveTo="-translate-x-full"
                        >
                            <DialogPanel className="relative flex w-72 flex-1 flex-col bg-card">
                                <button
                                    type="button"
                                    className="absolute right-3 top-3 z-10 text-muted-foreground hover:text-foreground"
                                    onClick={() => setMobileOpen(false)}
                                >
                                    <XMarkIcon className="h-6 w-6" />
                                </button>
                                {sidebarContent}
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </Transition>

            <aside className="hidden md:fixed md:inset-y-0 md:left-0 md:flex md:w-72 md:flex-col md:border-r md:border-border">
                {sidebarContent}
            </aside>

            <div className="md:pl-72">
                <div className="sticky top-0 z-30 flex h-14 items-center gap-2 border-b border-border bg-card px-4 md:hidden">
                    <button
                        type="button"
                        className="rounded-md p-1.5 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        onClick={() => setMobileOpen(true)}
                        title="Open menu"
                    >
                        <Bars3Icon className="h-6 w-6" />
                    </button>
                    <Link
                        href="/"
                        className="flex items-center gap-2 rounded-md px-1 py-0.5 transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-card"
                        aria-label="Go to dashboard"
                    >
                        <span className="flex h-7 w-7 items-center justify-center rounded-md bg-gradient-to-br from-primary to-accent text-primary-foreground shadow-sm">
                            <PresentationChartLineIcon className="h-4 w-4" />
                        </span>
                        <span className="font-heading font-semibold text-foreground">My Monitor</span>
                    </Link>
                </div>

                {header && (
                    <header className="border-b border-border bg-card px-6 py-5">
                        {header}
                    </header>
                )}

                <main className="px-4 py-6 sm:px-6 lg:px-8">{children}</main>
            </div>
        </div>
    );
}
