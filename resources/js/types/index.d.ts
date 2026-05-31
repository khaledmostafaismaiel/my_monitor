export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    family_id: number;
    email_verified_at?: string;
}

export interface Family {
    id: number;
    name: string;
}

export interface MonthYear {
    id: number;
    month: number;
    year: number;
    family_id: number;
}

export interface Wallet {
    id: number;
    name: string;
    family_id: number;
}

export interface Category {
    id: number;
    name: string;
    status?: 'active' | 'inactive';
    limit?: number | null;
    parent_id?: number | null;
    family_id: number;
    children?: Category[];
}

export type TransactionDirection = 'credit' | 'debit';
export type TransactionType = 'normal' | 'draft' | 'blueprint';

export interface Transaction {
    id: number;
    name: string;
    price: number;
    quantity: number;
    direction: TransactionDirection;
    type: TransactionType;
    comment?: string | null;
    date?: string | null;
    category_id: number;
    category?: Category;
    wallet_id?: number | null;
    wallet?: Wallet | null;
    month_year_id?: number | null;
    month_year?: MonthYear | null;
    user_id: number;
    family_id: number;
}

export type TodoStatus = 'pending' | 'in_progress' | 'completed';
export type TodoPriority = 'low' | 'medium' | 'high';
export type TodoScope = 'public' | 'private';

export interface Todo {
    id: number;
    title: string;
    description?: string | null;
    status: TodoStatus;
    priority: TodoPriority;
    scope: TodoScope;
    order: number;
    due_date?: string | null;
    parent_id?: number | null;
    month_year_id?: number | null;
    user_id: number;
    family_id: number;
    all_children?: Todo[];
    children?: Todo[];
    user?: User;
}

export interface PaginatorLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginator<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: PaginatorLink[];
    first_page_url: string | null;
    last_page_url: string | null;
    next_page_url: string | null;
    prev_page_url: string | null;
    path: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User | null;
        family: Family | null;
    };
    flash: {
        message: string | null;
        error: string | null;
    };
    ziggy: {
        url: string;
        port: number | null;
        defaults: Record<string, unknown>;
        routes: Record<string, unknown>;
        location: string;
    };
};
