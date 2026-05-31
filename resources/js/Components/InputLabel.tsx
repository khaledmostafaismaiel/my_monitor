import { LabelHTMLAttributes } from 'react';
import clsx from 'clsx';

export default function InputLabel({
    value,
    className = '',
    children,
    ...props
}: LabelHTMLAttributes<HTMLLabelElement> & { value?: string }) {
    return (
        <label
            {...props}
            className={clsx('block text-sm font-medium text-foreground', className)}
        >
            {value ?? children}
        </label>
    );
}
