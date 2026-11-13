
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Barber Finder</title>

    <link rel="icon" type="image/png"
    href="/assets/images/favicon.png?v=3">

    <style>
        body {
            margin: 0;
            font-family: Tahoma;
            background: #f4f4f4;
            text-align: center;
        }

        header {
            background: #222;
            color: white;
            padding: 20px;
        }

        header img {
            width: 100px;
        }

        .slider {
            width: 100%;
            height: 400px;
            position: relative;
            overflow: hidden;
        }

        .slide {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .slide.active {
            display: block;
        }

        .slider button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #222;
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            font-size: 20px;
        }

        .prev { left: 15px; }
        .next { right: 15px; }

        .content {
            padding: 40px;
        }
        
.banner {
    width: 100%;
    min-height: 250px;
    background: linear-gradient(
        rgba(0,0,0,0.55),
        rgba(0,0,0,0.55)
    ), url('/assets/images/banner.jpg');

    background-size: cover;
    background-position: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    text-align: center;
}

.banner h2 {
    font-size: 32px;
}

.banner a {
    background: #c99a36;
    color: white;
    padding: 12px 25px;
    text-decoration: none;
    border-radius: 8px;
}

    </style>
</head>

<body>

<header>
    <img src="/assets/images/logo.png" alt="Logo">
   <h1>به Barber Finder خوش آمدید</h1>
</header>
<section class="banner">
<p>
با بهترین آرایشگران آشنا شوید،
خدمات مورد نظر خود را انتخاب کنید
و به‌صورت آنلاین نوبت بگیرید.
</p>

    <a href="/pages/contact.php">
        اطلاعات بیشتر
    </a>

</section>

<div class="slider">

    <img class="slide active"
    src="/assets/images/slide1.jpg">

    <img class="slide"
    src="/assets/images/slide2.jpg">

    <img class="slide"
    src="/assets/images/slide3.jpg">

    <button class="prev" onclick="changeSlide(-1)">❮</button>
    <button class="next" onclick="changeSlide(1)">❯</button>

</div>



<div class="content">
    <h2>به Barber Finder خوش آمدید</h2>
    <p>آرایشگر مورد علاقه خود را پیدا کنید و آنلاین نوبت بگیرید.</p>

    <a href="/pages/about.php">درباره ما</a>
    |
    <a href="/pages/contact.php">تماس با ما</a>
</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll(".slide");

    function showSlide(index) {
        slides[currentSlide].classList.remove("active");

        currentSlide = (index + slides.length) % slides.length;

        slides[currentSlide].classList.add("active");
    }

    function changeSlide(direction) {
        showSlide(currentSlide + direction);
    }

    setInterval(() => {
        changeSlide(1);
    }, 4000);
</script>

</body>
</html>
