import { Link } from '@inertiajs/react';
import clsx from 'clsx';
import { PaginatorLink } from '@/types';

export default function Pagination({ links }: { links: PaginatorLink[] }) {
    if (links.length <= 3) return null;

    return (
        <nav className="mt-4 flex items-center justify-center gap-1">
            {links.map((link, i) => {
                const label = link.label
                    .replace('&laquo;', '«')
                    .replace('&raquo;', '»')
                    .replace(/Previous/i, '«')
                    .replace(/Next/i, '»');

                if (!link.url) {
                    return (
                        <span
                            key={i}
                            className="rounded-md border border-border px-3 py-1 text-sm text-muted-foreground"
                            dangerouslySetInnerHTML={{ __html: label }}
                        />
                    );
                }

                return (
                    <Link
                        key={i}
                        href={link.url}
                        preserveScroll
                        preserveState
                        className={clsx(
                            'rounded-md border px-3 py-1 text-sm transition-colors',
                            link.active
                                ? 'border-primary bg-primary text-primary-foreground'
                                : 'border-border text-foreground hover:border-primary hover:text-primary',
                        )}
                        dangerouslySetInnerHTML={{ __html: label }}
                    />
                );
            })}
        </nav>
    );
}
