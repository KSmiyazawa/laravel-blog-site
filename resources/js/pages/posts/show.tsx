import { Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { AppContent } from '@/components/app-content';
import { Button } from '@/components/ui/button';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { type Post } from '@/types/post';

interface Props {
    post: Post & {
        user: {
            name: string;
        };
    };
    canEdit: boolean;
}

export default function Show({ post, canEdit }: Props) {
    return (
        <AppShell>
            <AppContent>
                <div className="mx-auto max-w-3xl">
                    <Breadcrumbs
                        breadcrumbs={[
                            { title: 'Home', href: '/' },
                            { title: 'Blog', href: '/posts' },
                            { title: post.title, href: `/posts/${post.slug}` },
                        ]}
                    />

                    <article className="mt-8">
                        {post.featured_image && (
                            <img
                                src={`/storage/${post.featured_image}`}
                                alt={post.title}
                                className="w-full h-64 object-cover rounded-lg mb-8"
                            />
                        )}

                        <h1 className="text-4xl font-bold mb-4">{post.title}</h1>

                        <div className="flex items-center text-sm text-neutral-600 dark:text-neutral-400 mb-8">
                            <span>By {post.user.name}</span>
                            <span className="mx-2">•</span>
                            <time dateTime={post.created_at}>
                                {new Date(post.created_at).toLocaleDateString()}
                            </time>
                        </div>

                        <div className="prose dark:prose-invert max-w-none">
                            {post.content}
                        </div>

                        {canEdit && (
                            <div className="mt-8 flex gap-4">
                                <Link href={`/posts/${post.slug}/edit`}>
                                    <Button>Edit Post</Button>
                                </Link>
                                <Link
                                    href={`/posts/${post.slug}`}
                                    method="delete"
                                    as="button"
                                    className="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                                >
                                    Delete Post
                                </Link>
                            </div>
                        )}
                    </article>
                </div>
            </AppContent>
        </AppShell>
    );
} 