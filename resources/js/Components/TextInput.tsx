import {
    forwardRef,
    InputHTMLAttributes,
    useEffect,
    useImperativeHandle,
    useRef,
} from 'react';
import clsx from 'clsx';

export default forwardRef(function TextInput(
    {
        type = 'text',
        className = '',
        isFocused = false,
        ...props
    }: InputHTMLAttributes<HTMLInputElement> & { isFocused?: boolean },
    ref,
) {
    const localRef = useRef<HTMLInputElement>(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) localRef.current?.focus();
    }, [isFocused]);

    return (
        <input
            {...props}
            type={type}
            ref={localRef}
            className={clsx(
                'block w-full rounded-md border-input bg-card text-foreground shadow-sm placeholder:text-muted-foreground focus:border-ring focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50',
                className,
            )}
        />
    );
});
