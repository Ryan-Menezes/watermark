<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marca d'água</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/brands.min.css" integrity="sha512-L+sMmtHht2t5phORf0xXFdTC0rSlML1XcraLTrABli/0MMMylsJi3XA23ReVQkZ7jLkOEIMicWGItyK4CAt2Xw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/fontawesome.min.css" integrity="sha512-cHxvm20nkjOUySu7jdwiUxgGy11vuVPE9YeK89geLMLMMEOcKFyS2i+8wo0FOwyQO/bL8Bvq1KMsqK4bbOsPnA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/regular.min.css" integrity="sha512-3YMBYASBKTrccbNMWlnn0ZoEOfRjVs9qo/dlNRea196pg78HaO0H/xPPO2n6MIqV6CgTYcWJ1ZB2EgWjeNP6XA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/solid.min.css" integrity="sha512-bdTSJB23zykBjGDvyuZUrLhHD0Rfre0jxTd0/jpTbV7sZL8DCth/88aHX0bq2RV8HK3zx5Qj6r2rRU/Otsjk+g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/svg-with-js.min.css" integrity="sha512-kykcz2VnxuCLnfiymkPqtsNceqEghEDiHWWYMa/nOwdutxeFGZsUi1+TEWT4MyesfxybNGpJNCVXzEPXSO8aKQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/v4-font-face.min.css" integrity="sha512-p0AmrzKP8l63xoFw9XB99oaYa40RUgDuMpdkrzFhi4HPHzO3bzyN2qP6bepe43OP3yj9+eGQEJGIGPcno1JdPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/v4-shims.min.css" integrity="sha512-h2Z3EnLjRZp3KQxGYzT6SyqPrWIlmjnlJz/8q3BoZo2IN51insCNN7nmA4WHoe9eu1H5B3xLdLwKiVF8kF+Ewg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/v5-font-face.min.css" integrity="sha512-H2YXTLk5bs3DqvCfOEFsHmsjKW/qLp8SqsqVuPVwZzA5WFudPvPJisFKba2Km3sZNNBapYsZjSMTmRVcfxb5yw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <form action="generate.php" method="POST" enctype="multipart/form-data" class="form form-watermark">
        <div class="form-inputs">
            <div>
                <div class="form-group mb-4">
                    <label for="images" class="form-label">
                        <strong>Imagens:</strong>
                    </label>
                    <input class="form-control" id="images" type="file" accept="image/*" name="images[]" multiple required />
                </div>

                <div class="form-group mb-4">
                    <label for="watermark" class="form-label">
                        <strong>Marca d'água:</strong>
                    </label>
                    <input class="form-control" id="watermark" type="file" accept="image/*" name="watermark" required />
                </div>
            </div>

            <div class="container-btn">
                <button class="btn" type="submit">
                    Adicionar marca d'água
                    <i class="fa-solid fa-angles-right"></i>
                </button>
            </div>
        </div>
        <div>
            <div class="form-options">
                <label>
                    <input type="radio" name="type" value="0" required checked />

                    <div class="top-left">
                        <i class="fa-solid fa-square"></i>
                    </div>
                </label>

                <label>
                    <input type="radio" name="type" value="1" required />

                    <div>
                        <i class="fa-solid fa-square"></i>
                    </div>
                </label>

                <label>
                    <input type="radio" name="type" value="2" required />

                    <div class="top-right">
                        <i class="fa-solid fa-square"></i>
                    </div>
                </label>

                <label>
                    <input type="radio" name="type" value="3" required />

                    <div class="bottom-left">
                        <i class="fa-solid fa-square"></i>
                    </div>
                </label>

                <label>
                    <input type="radio" name="type" value="4" required />

                    <div class="grid">
                        <i class="fa-sharp fa-solid fa-table-cells"></i>
                    </div>
                </label>

                <label>
                    <input type="radio" name="type" value="5" required />

                    <div class="bottom-right">
                        <i class="fa-solid fa-square"></i>
                    </div>
                </label>

                <label>
                    <input type="radio" name="type" value="6" />

                    <div class="grid">
                        <i class="fa-sharp fa-solid fa-table-cells"></i>
                    </div>
                </label>
            </div>
        </div>
    </form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>