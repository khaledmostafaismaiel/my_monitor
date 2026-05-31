import { HTMLAttributes } from 'react';
import clsx from 'clsx';

export default function InputError({
    message,
    className = '',
    ...props
}: HTMLAttributes<HTMLParagraphElement> & { message?: string }) {
    if (!message) return null;
    return (
        <p {...props} className={clsx('text-sm text-destructive', className)}>
            {message}
        </p>
    );
}
