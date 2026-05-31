import { PropsWithChildren } from 'react';
import FlashBanner from '@/Components/FlashBanner';

export default function Guest({ children }: PropsWithChildren) {
    return (
        <div className="relative flex min-h-screen items-center justify-center overflow-hidden bg-background px-4 py-8">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,hsl(var(--primary)/0.1),transparent_60%),radial-gradient(circle_at_bottom_right,hsl(var(--accent)/0.1),transparent_55%)]" />
            <FlashBanner />
            <div className="relative w-full max-w-md">{children}</div>
        </div>
    );
}
