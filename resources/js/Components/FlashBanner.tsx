import { usePage } from '@inertiajs/react';
import { CheckCircleIcon, ExclamationTriangleIcon, XMarkIcon } from '@heroicons/react/24/outline';
import { useEffect, useState } from 'react';
import clsx from 'clsx';

const TOAST_EVENT = 'flash:show';

export type ToastKind = 'success' | 'error';

export function toast(text: string, kind: ToastKind = 'success') {
    if (typeof window === 'undefined') return;
    window.dispatchEvent(new CustomEvent(TOAST_EVENT, { detail: { text, kind } }));
}

export default function FlashBanner() {
    const { flash } = usePage().props;
    const [active, setActive] = useState<{ text: string; kind: ToastKind } | null>(null);

    useEffect(() => {
        if (flash.error) setActive({ text: flash.error, kind: 'error' });
        else if (flash.message) setActive({ text: flash.message, kind: 'success' });
    }, [flash.message, flash.error]);

    useEffect(() => {
        const handler = (e: Event) => {
            const detail = (e as CustomEvent<{ text: string; kind: ToastKind }>).detail;
            if (detail?.text) setActive({ text: detail.text, kind: detail.kind ?? 'success' });
        };
        window.addEventListener(TOAST_EVENT, handler);
        return () => window.removeEventListener(TOAST_EVENT, handler);
    }, []);

    useEffect(() => {
        if (!active) return;
        const t = setTimeout(() => setActive(null), 4000);
        return () => clearTimeout(t);
    }, [active]);

    if (!active) return null;

    const isError = active.kind === 'error';

    return (
        <div className="fixed left-1/2 top-4 z-[60] -translate-x-1/2">
            <div
                className={clsx(
                    'flex items-center gap-3 rounded-lg border px-4 py-3 shadow-lg backdrop-blur',
                    isError
                        ? 'border-destructive/30 bg-destructive/10 text-destructive'
                        : 'border-success/30 bg-success/10 text-success',
                )}
            >
                {isError ? (
                    <ExclamationTriangleIcon className="h-5 w-5 flex-shrink-0" />
                ) : (
                    <CheckCircleIcon className="h-5 w-5 flex-shrink-0" />
                )}
                <span className="text-sm font-medium">{active.text}</span>
                <button
                    type="button"
                    onClick={() => setActive(null)}
                    className="ml-2 text-current opacity-60 hover:opacity-100"
                >
                    <XMarkIcon className="h-4 w-4" />
                </button>
            </div>
        </div>
    );
}
