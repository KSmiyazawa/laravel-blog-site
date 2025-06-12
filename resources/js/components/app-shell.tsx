import { SharedData } from '@/types';
import { cn } from '@/lib/utils';
import { AppHeader } from './app-header';

interface AppShellProps {
    children: React.ReactNode;
    variant?: 'default' | 'sidebar' | 'header';
}

export function AppShell({ children, variant = 'default' }: AppShellProps) {
    return (
        <div className={cn(
            "flex min-h-screen w-full flex-col",
            variant === 'sidebar' && "lg:pl-[var(--sidebar-width)]"
        )}>
            {variant === 'default' && <AppHeader />}
            <div className="flex-1">
                {children}
            </div>
        </div>
    );
}
