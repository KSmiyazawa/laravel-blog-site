import { Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { AppContent } from '@/components/app-content';
import { Button } from '@/components/ui/button';
import { usePage } from '@inertiajs/react';
import { type SharedData } from '@/types';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppShell>
            <AppContent>
                <div className="relative isolate">
                    <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold tracking-tight sm:text-6xl">
                                Welcome to Our Blog
                            </h1>
                            <p className="mt-6 text-lg leading-8 text-neutral-600 dark:text-neutral-400">
                                Discover stories, thinking, and expertise from writers on any topic.
                            </p>
                            <div className="mt-10 flex items-center justify-center gap-x-6">
                                <Link href="/posts">
                                    <Button size="lg">
                                        Read Blog Posts
                                    </Button>
                                </Link>
                                { !auth.user && (
                                    <Link href="/login" className="text-sm font-semibold leading-6">
                                        Sign in <span aria-hidden="true">→</span>
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </AppContent>
        </AppShell>
    );
}
