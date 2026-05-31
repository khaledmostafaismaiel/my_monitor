import { SelectHTMLAttributes, forwardRef } from 'react';
import clsx from 'clsx';

export type SelectOption = { value: string | number; label: string };

const Select = forwardRef<
    HTMLSelectElement,
    SelectHTMLAttributes<HTMLSelectElement> & {
        options: SelectOption[];
        placeholder?: string;
    }
>(function Select({ options, placeholder, className = '', ...props }, ref) {
    return (
        <select
            ref={ref}
            {...props}
            className={clsx(
                'block w-full rounded-md border-input bg-card text-foreground shadow-sm focus:border-ring focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50',
                className,
            )}
        >
            {placeholder !== undefined && <option value="">{placeholder}</option>}
            {options.map((o) => (
                <option key={o.value} value={o.value}>
                    {o.label}
                </option>
            ))}
        </select>
    );
});

export default Select;
