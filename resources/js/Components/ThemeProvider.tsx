import { createContext, useCallback, useContext, useEffect, useMemo, useState, PropsWithChildren } from 'react';
import { Theme, ResolvedTheme, applyTheme, getStoredTheme, resolveTheme, setStoredTheme } from '@/lib/theme';

type ThemeContextValue = {
    theme: Theme;
    resolvedTheme: ResolvedTheme;
    setTheme: (theme: Theme) => void;
};

const ThemeContext = createContext<ThemeContextValue | null>(null);

export function ThemeProvider({ children }: PropsWithChildren) {
    const [theme, setThemeState] = useState<Theme>(() => getStoredTheme());
    const [resolvedTheme, setResolvedTheme] = useState<ResolvedTheme>(() => resolveTheme(getStoredTheme()));

    const setTheme = useCallback((next: Theme) => {
        setStoredTheme(next);
        setThemeState(next);
        applyTheme(next);
        setResolvedTheme(resolveTheme(next));
    }, []);

    useEffect(() => {
        applyTheme(theme);
        setResolvedTheme(resolveTheme(theme));
    }, [theme]);

    useEffect(() => {
        if (typeof window === 'undefined') return;
        const mq = window.matchMedia('(prefers-color-scheme: dark)');
        const onChange = () => {
            if (getStoredTheme() === 'system') {
                applyTheme('system');
                setResolvedTheme(resolveTheme('system'));
            }
        };
        mq.addEventListener('change', onChange);
        return () => mq.removeEventListener('change', onChange);
    }, []);

    const value = useMemo(() => ({ theme, resolvedTheme, setTheme }), [theme, resolvedTheme, setTheme]);
    return <ThemeContext.Provider value={value}>{children}</ThemeContext.Provider>;
}

export function useTheme(): ThemeContextValue {
    const ctx = useContext(ThemeContext);
    if (!ctx) throw new Error('useTheme must be used within ThemeProvider');
    return ctx;
}
