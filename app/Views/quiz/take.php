<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ทำแบบทดสอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4><?= $quiz['qui_title'] ?></h4>
            </div>
            <div class="card-body">
                <form action="/quiz/submit" method="post">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['qui_id'] ?>">
                    
                    <?php foreach($questions as $index => $q): ?>
                        <div class="mb-4">
                            <h5><?= ($index+1) ?>. <?= $q['que_text'] ?></h5>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?= $q['que_id'] ?>]" value="a" required>
                                <label class="form-check-label"><?= $q['que_opt_a'] ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?= $q['que_id'] ?>]" value="b">
                                <label class="form-check-label"><?= $q['que_opt_b'] ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?= $q['que_id'] ?>]" value="c">
                                <label class="form-check-label"><?= $q['que_opt_c'] ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?= $q['que_id'] ?>]" value="d">
                                <label class="form-check-label"><?= $q['que_opt_d'] ?></label>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-success btn-lg w-100">ส่งคำตอบ</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>