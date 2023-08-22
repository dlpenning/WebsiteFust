<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <title><?= fust_get_title() ?> - FUST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&family=League+Spartan:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="body-inner">
        <header class="app-header">
            <div class="top-navbar">
                <!--<a href="#"><i class="fab fa-tiktok"></i></a>-->
                <!--<a href="#"><i class="fab fa-linkedin"></i></a>-->
                <a href="https://www.instagram.com/fust_tilburg/"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="main-navbar">
                <div class="main-navbar-inner">
                    <nav>
                        <svg width="33" height="51" viewBox="0 0 33 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0 1.01936C0 0.456384 0.46261 0 1.03327 0H31.9667C32.5374 0 33 0.456384 33 1.01936V4.52342C33 5.0864 32.5374 5.54279 31.9667 5.54279H20.3425C19.7718 5.54279 19.3092 5.99917 19.3092 6.56215V42.8132C19.3092 43.3763 18.8466 43.8326 18.2759 43.8326H14.7241C14.1534 43.8326 13.6908 43.3763 13.6908 42.8132V6.56215C13.6908 5.99917 13.2282 5.54279 12.6575 5.54279H1.03327C0.46261 5.54279 0 5.0864 0 4.52342V1.01936Z"
                                fill="#7958B3" />
                            <path
                                d="M21.9569 7.13553H32.2998C32.6865 7.13553 33 7.44478 33 7.82634V34.722C33 43.7121 25.6127 51 16.5 51C7.38729 51 0 43.7121 0 34.722V7.82634C0 7.44478 0.313491 7.13553 0.700201 7.13553H11.0431C11.6137 7.13553 12.0763 7.59188 12.0763 8.15489V10.767C12.0763 11.33 11.6137 11.7864 11.0431 11.7864H6.13503C5.8497 11.7864 5.6184 12.0146 5.6184 12.2961V13.8251C5.6184 14.1066 5.8497 14.3348 6.13503 14.3348H9.29941C9.8701 14.3348 10.3327 14.7911 10.3327 15.3541V17.138C10.3327 17.701 9.8701 18.1574 9.29941 18.1574H5.83699C5.71627 18.1574 5.6184 18.2539 5.6184 18.373V34.5946C5.6184 40.5235 10.4903 45.3298 16.5 45.3298C22.5097 45.3298 27.3816 40.5235 27.3816 34.5946V18.373C27.3816 18.2539 27.2838 18.1574 27.163 18.1574H23.7006C23.1299 18.1574 22.6673 17.701 22.6673 17.138V15.3541C22.6673 14.7911 23.1299 14.3348 23.7006 14.3348H26.865C27.1503 14.3348 27.3816 14.1066 27.3816 13.8251V12.2961C27.3816 12.0146 27.1503 11.7864 26.865 11.7864H21.9569C21.3863 11.7864 20.9237 11.33 20.9237 10.767V8.15489C20.9237 7.59188 21.3863 7.13553 21.9569 7.13553Z"
                                fill="#C67DFF" />
                            <path
                                d="M24.863 22.0276V26.5111H20.4034V23.8403V23.7129H20.2742H12.7257H12.5965V23.8403V28.5039V28.6314H12.7257H22.1078L24.863 31.3548V39.6439L22.1078 42.3673H10.8921L8.13696 39.6439V35.1604H12.5965V37.8311V37.9585H12.7257H20.2742H20.4034V37.8311V33.1675V33.0401H20.2742H10.8921L8.13696 30.3167V22.0276L10.8921 19.3042H22.1078L24.863 22.0276Z"
                                fill="#FFFF95" stroke="#7958B3" />
                            <path d="M19.3092 27.8414H13.6908V34.2124H19.3092V27.8414Z" fill="#7958B3" />
                        </svg>
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'fust-app-header',
                            )
                        );
                        ?>
                    </nav>
                    <div class="right-nav">
                        <a href="/become-a-member" class="item button primary">Become a member</a>
                        <!--<i class="fa fa-search"></i>-->
                    </div>
                </div>
            </div>
        </header>