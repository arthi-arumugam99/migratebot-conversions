// components/Comments.tsx
// MIGRATED: Converted from comments.php PHP template to React Server/Client component

'use client';

import { useState, useEffect } from 'react';

// TypeScript types for WordPress REST API comment objects
interface WPComment {
  id: number;
  parent: number;
  author: number;
  author_name: string;
  author_url: string;
  author_avatar_urls: {
    [key: string]: string;
    '24': string;
    '48': string;
    '96': string;
  };
  date: string;
  content: {
    rendered: string;
  };
  status: string;
  type: string;
  link: string;
  meta: Record<string, unknown>;
}

interface CommentFormData {
  author_name: string;
  author_email: string;
  author_url: string;
  content: string;
}

interface CommentsProps {
  postId: number;
  postTitle: string;
  commentsOpen?: boolean;
}

// MIGRATED: Recursive comment component replaces wp_list_comments() walker pattern
function CommentItem({
  comment,
  allComments,
  depth = 0,
}: {
  comment: WPComment;
  allComments: WPComment[];
  depth?: number;
}) {
  const childComments = allComments.filter((c) => c.parent === comment.id);
  const avatarUrl = comment.author_avatar_urls?.['48'] || comment.author_avatar_urls?.['96'];
  const formattedDate = new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(comment.date));

  return (
    <li
      id={`comment-${comment.id}`}
      className={`comment depth-${depth}`}
      style={{ marginLeft: depth > 0 ? '2rem' : undefined }}
    >
      <article className="comment-body">
        <footer className="comment-meta">
          <div className="comment-author vcard">
            {avatarUrl && (
              // TODO: Manual review needed -- replace with Next.js <Image> from 'next/image' and add WordPress domain to next.config.js remotePatterns
              // MIGRATED: wp_get_avatar() replaced with direct avatar URL from REST API response
              <img
                src={avatarUrl}
                alt={comment.author_name}
                className="avatar"
                width={48}
                height={48}
                loading="lazy"
              />
            )}
            <b className="fn">
              {comment.author_url ? (
                <a href={comment.author_url} rel="external nofollow" className="url">
                  {comment.author_name}
                </a>
              ) : (
                <span>{comment.author_name}</span>
              )}
            </b>
          </div>
          <div className="comment-metadata">
            {/* MIGRATED: comment_date() / comment_time() replaced with Intl.DateTimeFormat */}
            <time dateTime={comment.date}>{formattedDate}</time>
          </div>
        </footer>

        <div
          className="comment-content"
          // MIGRATED: comment_text() replaced with dangerouslySetInnerHTML for trusted WP-rendered HTML
          // TODO: Manual review needed -- sanitize HTML if comments can contain user-submitted content
          dangerouslySetInnerHTML={{ __html: comment.content.rendered }}
        />
      </article>

      {/* MIGRATED: Nested/child comments rendered recursively, replacing Walker_Comment depth handling */}
      {childComments.length > 0 && (
        <ol className="children">
          {childComments.map((child) => (
            <CommentItem
              key={child.id}
              comment={child}
              allComments={allComments}
              depth={depth + 1}
            />
          ))}
        </ol>
      )}
    </li>
  );
}

// MIGRATED: comment_form() replaced with React controlled form component
function CommentForm({
  postId,
  onCommentSubmitted,
}: {
  postId: number;
  onCommentSubmitted: (comment: WPComment) => void;
}) {
  const [formData, setFormData] = useState<CommentFormData>({
    author_name: '',
    author_email: '',
    author_url: '',
    content: '',
  });
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [submitSuccess, setSubmitSuccess] = useState(false);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  // MIGRATED: wp_ajax_* / comment submission replaced with fetch POST to WP REST API comments endpoint
  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setIsSubmitting(true);
    setSubmitError(null);

    try {
      const apiBase = process.env.NEXT_PUBLIC_WORDPRESS_URL || '';

      const response = await fetch(`${apiBase}/wp-json/wp/v2/comments`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          post: postId,
          author_name: formData.author_name,
          author_email: formData.author_email,
          author_url: formData.author_url || undefined,
          content: formData.content,
        }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(
          errorData?.message || 'Failed to submit comment. Please try again.'
        );
      }

      const newComment: WPComment = await response.json();
      setSubmitSuccess(true);
      setFormData({ author_name: '', author_email: '', author_url: '', content: '' });
      onCommentSubmitted(newComment);
    } catch (err) {
      setSubmitError(err instanceof Error ? err.message : 'An unexpected error occurred.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    // MIGRATED: comment_form() PHP function replaced with React form component
    <div id="respond" className="comment-respond">
      <h3 id="reply-title" className="comment-reply-title">
        Leave a Reply
      </h3>

      {submitSuccess && (
        <p className="comment-notes success-message">
          {/* MIGRATED: Inline notice replaces WP admin moderation notice */}
          Your comment has been submitted and is awaiting moderation. Thank you!
        </p>
      )}

      <form
        id="commentform"
        className="comment-form"
        onSubmit={handleSubmit}
        noValidate
      >
        {submitError && (
          <p className="comment-form-error" role="alert">
            {submitError}
          </p>
        )}

        <p className="comment-notes">
          <span>Your email address will not be published.</span>{' '}
          <span className="required-field-message">
            Required fields are marked <span className="required" aria-hidden="true">*</span>
          </span>
        </p>

        {/* MIGRATED: comment_form_fields replaced with controlled React inputs */}
        <div className="comment-form-comment">
          <label htmlFor="comment">
            Comment <span className="required" aria-hidden="true">*</span>
          </label>
          <textarea
            id="comment"
            name="content"
            value={formData.content}
            onChange={handleChange}
            rows={8}
            maxLength={65525}
            required
            disabled={isSubmitting}
            aria-required="true"
          />
        </div>

        <div className="comment-form-author">
          <label htmlFor="author">
            Name <span className="required" aria-hidden="true">*</span>
          </label>
          <input
            type="text"
            id="author"
            name="author_name"
            value={formData.author_name}
            onChange={handleChange}
            size={30}
            maxLength={245}
            required
            autoComplete="name"
            disabled={isSubmitting}
            aria-required="true"
          />
        </div>

        <div className="comment-form-email">
          <label htmlFor="email">
            Email <span className="required" aria-hidden="true">*</span>
          </label>
          <input
            type="email"
            id="email"
            name="author_email"
            value={formData.author_email}
            onChange={handleChange}
            size={30}
            maxLength={100}
            required
            autoComplete="email"
            disabled={isSubmitting}
            aria-required="true"
          />
        </div>

        <div className="comment-form-url">
          <label htmlFor="url">Website</label>
          <input
            type="url"
            id="url"
            name="author_url"
            value={formData.author_url}
            onChange={handleChange}
            size={30}
            maxLength={200}
            autoComplete="url"
            disabled={isSubmitting}
          />
        </div>

        {/* MIGRATED: wp_nonce_field() removed; TODO: implement CSRF protection via NextAuth or custom token if needed */}
        {/* TODO: Manual review needed -- add CSRF token or NextAuth session validation if comment auth is required */}

        <p className="form-submit">
          <input
            type="submit"
            id="submit"
            className="submit"
            value={isSubmitting ? 'Submitting…' : 'Post Comment'}
            disabled={isSubmitting}
          />
        </p>
      </form>
    </div>
  );
}

// MIGRATED: Main Comments component replaces the entire comments.php template
// post_password_required() check must be handled by the parent page component before rendering this
export default function Comments({ postId, postTitle, commentsOpen = true }: CommentsProps) {
  const [comments, setComments] = useState<WPComment[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [fetchError, setFetchError] = useState<string | null>(null);

  // MIGRATED: wp_list_comments() + WP_Comment_Query replaced with fetch to /wp-json/wp/v2/comments
  useEffect(() => {
    const fetchComments = async () => {
      setIsLoading(true);
      setFetchError(null);

      try {
        const apiBase = process.env.NEXT_PUBLIC_WORDPRESS_URL || '';
        // TODO: Manual review needed -- add pagination support via X-WP-TotalPages header if post has many comments
        const response = await fetch(
          `${apiBase}/wp-json/wp/v2/comments?post=${postId}&per_page=100&orderby=date&order=asc`,
          { next: { revalidate: 60 } } as RequestInit
        );

        if (!response.ok) {
          throw new Error('Failed to fetch comments.');
        }

        const data: WPComment[] = await response.json();
        setComments(data);
      } catch (err) {
        setFetchError(err instanceof Error ? err.message : 'Could not load comments.');
      } finally {
        setIsLoading(false);
      }
    };

    fetchComments();
  }, [postId]);

  // MIGRATED: have_comments() check replaced with comments array length check
  const topLevelComments = comments.filter((c) => c.parent === 0);
  const commentCount = comments.length;

  const handleCommentSubmitted = (newComment: WPComment) => {
    setComments((prev) => [...prev, newComment]);
  };

  // MIGRATED: comments_title conditional logic preserved from original PHP
  const renderCommentsTitle = () => {
    if (commentCount === 1) {
      // MIGRATED: printf with esc_html__ replaced with JSX template literal
      return (
        <>
          One thought on &ldquo;<span>{postTitle}</span>&rdquo;
        </>
      );
    }
    // MIGRATED: _nx() plural form replaced with simple JS conditional
    return (
      <>
        {commentCount} thoughts on &ldquo;<span>{postTitle}</span>&rdquo;
      </>
    );
  };

  return (
    // MIGRATED: <div id="comments" class="comments-area"> preserved as JSX className
    <div id="comments" className="comments-area">
      {isLoading && (
        <p className="comments-loading">Loading comments&hellip;</p>
      )}

      {fetchError && (
        <p className="comments-error" role="alert">
          {fetchError}
        </p>
      )}

      {/* MIGRATED: if (have_comments()) block replaced with array length conditional */}
      {!isLoading && !fetchError && commentCount > 0 && (
        <>
          <h2 className="comments-title">
            {renderCommentsTitle()}
          </h2>
          {/* .comments-title */}

          {/* MIGRATED: the_comments_navigation() removed -- TODO: add pagination component if needed for large comment counts */}

          {/* MIGRATED: wp_list_comments() replaced with recursive CommentItem component */}
          <ol className="comment-list">
            {topLevelComments.map((comment) => (
              <CommentItem
                key={comment.id}
                comment={comment}
                allComments={comments}
                depth={0}
              />
            ))}
          </ol>
          {/* .comment-list */}

          {/* MIGRATED: if (!comments_open()) notice preserved as React conditional */}
          {!commentsOpen && (
            <p className="no-comments">Comments are closed.</p>
          )}
        </>
      )}

      {/* MIGRATED: comment_form() replaced with React CommentForm component */}
      {commentsOpen && (
        <CommentForm postId={postId} onCommentSubmitted={handleCommentSubmitted} />
      )}
    </div>
    // #comments
  );
}