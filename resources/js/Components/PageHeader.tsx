import { PropsWithChildren, ReactNode } from 'react';

export default function PageHeader({
    title,
    description,
    actions,
}: PropsWithChildren<{
    title: string;
    description?: string;
    actions?: ReactNode;
}>) {
    return (
        <div className="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 className="font-heading text-2xl font-bold text-foreground">{title}</h1>
                {description && <p className="text-sm text-muted-foreground">{description}</p>}
            </div>
            {actions && <div className="flex flex-wrap items-center gap-2">{actions}</div>}
        </div>
    );
}
