<?php require "includes.php";
head_page("Student feed");
$posts = all_rows(
    "SELECT p.*,u.full_name,u.avatar_color,u.program FROM posts p JOIN users u ON u.id=p.user_id ORDER BY p.id DESC",
);
$people = all_rows(
    "SELECT id,full_name,program,avatar_color FROM users LIMIT 4",
);
?>
<div class="hero"><div><h1>Connect. Learn. Grow.</h1><p>Your student community — ideas, projects, connections, and conversations.</p></div><div class="pill">🎓 &nbsp; CampusConnect Community</div></div>
<div class="grid"><aside><div class="panel"><h2>Community</h2><p class="muted">Discover student profiles, follow discussions and connect with your classmates.</p><a class="btn" href="directory.php">Explore students →</a></div><div class="panel"><h2>Suggested people</h2><?php foreach (
    $people
    as $u
): ?><a href="profile.php?id=<?= $u[
    "id"
] ?>" class="userline" style="margin:15px 0"><?= avatar(
    $u,
) ?><span><strong><?= e($u["full_name"]) ?></strong><small><?= e(
    $u["program"],
) ?></small></span></a><?php endforeach; ?></div></aside><section><div class="panel"><h2>Community feed</h2><?php if (
    me()
): ?><form action="post_create.php" method="post"><textarea name="body" placeholder="Share a campus update..." required></textarea><div style="text-align:right;margin-top:10px"><button class="btn">Publish post</button></div></form><?php else: ?><p class="muted">Log in to share a post, add friends, and comment.</p><a class="btn" href="login.php">Sign in</a><?php endif; ?></div><?php foreach (
    $posts
    as $post
): ?><article class="post"><div class="userline"><?= avatar(
    $post,
) ?><div><a href="profile.php?id=<?= $post["user_id"] ?>"><strong><?= e(
    $post["full_name"],
) ?></strong></a><small><?= e(
    $post["created_at"],
) ?></small></div></div><p><?= nl2br(
    e($post["body"]),
) ?></p><div class="comments"><b>Comments</b><?php
$comments = all_rows(
    "SELECT c.body,u.full_name FROM comments c JOIN users u ON c.user_id=u.id WHERE c.post_id=" .
        (int) $post["id"] .
        " ORDER BY c.id ASC",
);
foreach ($comments as $c): ?><div class="comment"><b><?= e(
    $c["full_name"],
) ?></b><p><?= nl2br(e($c["body"])) ?></p></div><?php endforeach;
if (
    me()
): ?><form method="post" action="comment_create.php" class="formrow"><input type="hidden" name="post_id" value="<?= $post[
    "id"
] ?>"><input name="body" required placeholder="Add a comment..."><button class="btn">Post</button></form><?php endif;
?></div></article><?php endforeach; ?></section></div>
<?php foot_page(); ?>
