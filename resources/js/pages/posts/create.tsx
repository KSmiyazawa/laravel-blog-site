import { Head, useForm } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { AppContent } from '@/components/app-content';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';

export default function CreatePost() {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        content: '',
        featured_image: null as File | null,
    });

    function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        if (e.target.files && e.target.files[0]) {
            setData('featured_image', e.target.files[0]);
        }
    }

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        post('/posts');
    }

    return (
        <AppShell>
            <AppContent>
                <div className="mx-auto max-w-3xl">
                    <Breadcrumbs
                        breadcrumbs={[
                            { title: 'Home', href: '/' },
                            { title: 'Blog', href: '/posts' },
                            { title: 'Create Post', href: '/posts/create' },
                        ]}
                    />

                    <div className="mt-8">
                        <Head title="Create Post" />
                        <div className="rounded-lg border bg-card p-6 shadow-sm">
                            <h1 className="text-2xl font-semibold mb-6">Create New Post</h1>
                            <form onSubmit={handleSubmit} className="space-y-6">
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
                                </div>

                                <div className="flex justify-end">
                                    <Button type="submit" disabled={processing}>
                                        Create Post
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
