import { Head, useForm } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { AppContent } from '@/components/app-content';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import { type Post } from '@/types/post';

interface Props {
    post: Post & {
        user: {
            name: string;
        };
    };
}

export default function EditPost({ post }: Props) {
    const { data, setData, post: submitPost, processing, errors } = useForm({
        title: post.title,
        content: post.content,
        featured_image: null as File | null,
        _method: 'PUT' as const,
    });

    function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0] ?? null;
        setData('featured_image', file);
    }

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        submitPost(`/posts/${post.slug}`, {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                // Reset the file input after successful submission
                const fileInput = document.getElementById('featured_image') as HTMLInputElement;
                if (fileInput) {
                    fileInput.value = '';
                }
            }
        });
    }

    return (
        <AppShell>
            <AppContent>
                <div className="mx-auto max-w-3xl">
                    <Breadcrumbs
                        breadcrumbs={[
                            { title: 'Home', href: '/' },
                            { title: 'Blog', href: '/posts' },
                            { title: post.title, href: `/posts/${post.slug}` },
                            { title: 'Edit', href: `/posts/${post.slug}/edit` },
                        ]}
                    />

                    <div className="mt-8">
                        <Head title={`Edit: ${post.title}`} />
                        <div className="rounded-lg border bg-card p-6 shadow-sm">
                            <h1 className="text-2xl font-semibold mb-6">Edit Post</h1>
                            <form onSubmit={handleSubmit} className="space-y-6" encType="multipart/form-data">
                                <div className="space-y-2">
                                    <Label htmlFor="title">Title</Label>
                                    <Input
                                        id="title"
                                        type="text"
                                        value={data.title}
                                        onChange={e => setData('title', e.target.value)}
                                        required
                                    />
                                    <InputError message={errors.title} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="content">Content</Label>
                                    <textarea
                                        id="content"
                                        value={data.content}
                                        onChange={e => setData('content', e.target.value)}
                                        rows={10}
                                        required
                                        className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    />
                                    <InputError message={errors.content} />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="featured_image">Featured Image</Label>
                                    <Input
                                        id="featured_image"
                                        type="file"
                                        accept="image/*"
                                        onChange={handleFileChange}
                                        className="cursor-pointer"
                                    />
                                    <InputError message={errors.featured_image} />
                                    {post.featured_image && (
                                        <div className="mt-2">
                                            <p className="text-sm text-muted-foreground mb-2">Current image:</p>
                                            <img
                                                src={`/storage/${post.featured_image}`}
                                                alt={post.title}
                                                className="w-32 h-32 object-cover rounded-lg"
                                            />
                                        </div>
                                    )}
                                </div>

                                <div className="flex justify-end">
                                    <Button type="submit" disabled={processing}>
                                        Update Post
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </AppContent>
        </AppShell>
    );
}
