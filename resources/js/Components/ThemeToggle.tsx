import { Menu, MenuButton, MenuItem, MenuItems, Transition } from '@headlessui/react';
import { ComputerDesktopIcon, MoonIcon, SunIcon, CheckIcon } from '@heroicons/react/24/outline';
import clsx from 'clsx';
import { Fragment } from 'react';
import { useTheme } from './ThemeProvider';
import { Theme } from '@/lib/theme';

const OPTIONS: { value: Theme; label: string; icon: typeof SunIcon }[] = [
    { value: 'light', label: 'Light', icon: SunIcon },
    { value: 'dark', label: 'Dark', icon: MoonIcon },
    { value: 'system', label: 'System', icon: ComputerDesktopIcon },
];

export default function ThemeToggle({ className }: { className?: string }) {
    const { theme, resolvedTheme, setTheme } = useTheme();
    const TriggerIcon = resolvedTheme === 'dark' ? MoonIcon : SunIcon;

    return (
        <Menu as="div" className={clsx('relative', className)}>
            <MenuButton
                className="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                title="Theme"
            >
                <TriggerIcon className="h-4 w-4" />
                <span>Theme</span>
                <span className="ml-auto text-xs capitalize text-muted-foreground/70">{theme}</span>
            </MenuButton>
            <Transition
                as={Fragment}
                enter="transition ease-out duration-100"
                enterFrom="opacity-0 scale-95"
                enterTo="opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="opacity-100 scale-100"
                leaveTo="opacity-0 scale-95"
            >
                <MenuItems className="absolute bottom-full left-0 mb-2 w-44 origin-bottom-left overflow-hidden rounded-md border border-border bg-popover py-1 text-sm shadow-lg ring-1 ring-foreground/5 focus:outline-none">
                    {OPTIONS.map((opt) => {
                        const Icon = opt.icon;
                        const active = theme === opt.value;
                        return (
                            <MenuItem key={opt.value}>
                                {({ focus }) => (
                                    <button
                                        type="button"
                                        onClick={() => setTheme(opt.value)}
                                        className={clsx(
                                            'flex w-full items-center gap-2 px-3 py-1.5 text-left text-popover-foreground transition-colors',
                                            focus && 'bg-muted',
                                        )}
                                    >
                                        <Icon className="h-4 w-4" />
                                        <span className="flex-1">{opt.label}</span>
                                        {active && <CheckIcon className="h-4 w-4 text-primary" />}
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
