import { User } from './index';

export interface Post {
    id: number;
    user_id: number;
    title: string;
    slug: string;
    content: string;
    featured_image: string | null;
    created_at: string;
    updated_at: string;
    user: User;
}

export interface CreatePostData {
    title: string;
    content: string;
    featured_image?: File;
}

export interface UpdatePostData extends Partial<CreatePostData> {
    id: number;
}
