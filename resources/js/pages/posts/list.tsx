import { Link } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { AppContent } from '@/components/app-content';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-react';
import { type Post } from '@/types/post';
import { type SharedData } from '@/types';

interface Props {
    posts: (Post & {
        user: {
            name: string;
        };
    })[];
}

export default function ListPosts({ posts }: Props) {
    const { auth } = usePage<SharedData>().props;

    const breadcrumbs = [
        { title: 'Home', href: '/' },
        { title: 'Blog', href: '/posts' },
    ];

    return (
        <AppShell>
            <AppContent>
                <div className="flex items-center justify-between mb-8">
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                    {auth.user && (
                        <Link href="/posts/create">
                            <Button>
                                <PlusIcon className="mr-2 h-4 w-4" />
                                New Post
                            </Button>
                        </Link>
                    )}
                </div>

                <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    {posts.map((post) => (
                        <article key={post.id} className="group flex flex-col space-y-4 rounded-lg border bg-card p-6 shadow-sm transition-colors hover:bg-accent/5">
                            {post.featured_image && (
                                <div className="aspect-video overflow-hidden rounded-lg">
                                    <img
                                        src={`/storage/${post.featured_image}`}
                                        alt={post.title}
                                        className="h-full w-full object-cover transition-transform group-hover:scale-105"
                                    />
                                </div>
                            )}
                            <div className="flex-1 space-y-4">
                                <div>
                                    <h2 className="text-2xl font-semibold tracking-tight">
                                        <Link href={`/posts/${post.slug}`} className="hover:underline">
                                            {post.title}
                                        </Link>
                                    </h2>
                                    <div className="mt-2 flex items-center text-sm text-muted-foreground">
                                        <span>By {post.user.name}</span>
                                        <span className="mx-2">•</span>
                                        <time dateTime={post.created_at}>
                                            {new Date(post.created_at).toLocaleDateString()}
                                        </time>
                                    </div>
                                </div>
                                <p className="line-clamp-3">
                                    {post.content}
                                </p>
                                <Link 
                                    href={`/posts/${post.slug}`}
                                    className="inline-flex items-center text-sm font-medium text-blue-600 hover:underline"
                                >
                                    Read More
                                    <svg
                                        className="ml-1 h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </Link>
                            </div>
                        </article>
                    ))}
                </div>

                {posts.length === 0 && (
                    <div className="flex flex-col items-center justify-center py-12 text-center">
                        <h2 className="text-2xl font-semibold">No posts yet</h2>
                        <p className="mt-2 text-muted-foreground">
                            {auth.user
                                ? 'Be the first to create a post!'
                                : 'Sign in to create the first post.'}
                        </p>
                    </div>
                )}
            </AppContent>
        </AppShell>
    );
}
