import * as React from 'react';
import { cn } from '@/lib/utils';

interface AppContentProps extends React.ComponentProps<'main'> {
    variant?: 'default' | 'sidebar';
}

export function AppContent({ children, variant = 'default', className, ...props }: AppContentProps) {
    return (
        <main className={cn(
            "mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 p-4",
            variant === 'sidebar' && "lg:pl-0",
            className
        )} {...props}>
            {children}
        </main>
    );
}
