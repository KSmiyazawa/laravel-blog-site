import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

const settingsNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
        icon: null,
    },
    {
        title: 'Password',
        href: '/settings/password',
        icon: null,
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
        icon: null,
    },
];

export default function SettingsLayout({ children }: PropsWithChildren) {
    const { url } = usePage();

    return (
        <div className="space-y-6">
            <Heading title="Settings" description="Manage your profile and account settings" />

            <div className="flex flex-col space-y-8">
                <nav className="flex space-x-2 border-b border-sidebar-border/70 pb-4">
                    {settingsNavItems.map((item, index) => (
                        <Button
                            key={`${item.href}-${index}`}
                            size="sm"
                            variant="ghost"
                            asChild
                            className={cn('justify-start', {
                                'bg-muted': url === item.href,
                            })}
                        >
                            <Link href={item.href} prefetch>
                                {item.title}
                            </Link>
                        </Button>
                    ))}
                </nav>

                <div className="flex-1">
                    <section className="max-w-xl space-y-12">{children}</section>
                </div>
            </div>
        </div>
    );
}
