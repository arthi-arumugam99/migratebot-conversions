"use client";
// MIGRATED: added "use client" — uses onClick event handler and Router.push (client-side navigation)

import React from "react";
import { useRouter } from "next/navigation";
// MIGRATED: useRouter — changed from next/router to next/navigation
import ReactMarkdown from "react-markdown";

export type PostProps = {
  id: string;
  title: string;
  author: {
    name: string;
    email: string;
  } | null;
  content: string;
  published: boolean;
};

const Post: React.FC<{ post: PostProps }> = ({ post }) => {
  const router = useRouter();
  // MIGRATED: useRouter — replaced static Router import with useRouter hook from next/navigation
  const authorName = post.author ? post.author.name : "Unknown author";
  return (
    <div onClick={() => router.push(`/p/${post.id}`)}>
      {/* MIGRATED: Link — router.push no longer accepts separate pathname/as arguments; use template literal URL directly */}
      <h2>{post.title}</h2>
      <small>By {authorName}</small>
      <ReactMarkdown children={post.content} />
      <style jsx>{`
        div {
          color: inherit;
          padding: 2rem;
        }
      `}</style>
    </div>
  );
};

export default Post;