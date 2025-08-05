<?php require base_path('views/partials/head.php'); ?>

<?php require base_path('views/partials/nav.php'); ?>

<main>
    
    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
                <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Log into your Account</h2>
            </div>

            <form class="mt-8 space-y-6" action="/session" method="POST">
                <div class="-space-y-px rounded-md shadow-sm">
                    <div>
                        <label for="email" class="block text-sm/6 font-medium text-gray-900">
                            Email address
                        </label>
                        <input id="email"
                               name="email"
                               type="email"
                               autocomplete="email"
                               required
                               class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                               placeholder="Email address"
                               value="<?= old('email') ?>">
                    </div>

                    <div>
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">
                                Password
                            </label>
                            <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>
                </div>  
                
                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Log In
                    </button>
                </div>

                <ul>
                        <?php if (isset($errors['email'])) : ?>
                                <p class="text-red-500 text-xs mt-2"><?= $errors['email'] ?></p>
                        <?php endif; ?>

                        <?php if (isset($errors['password'])) : ?>
                            <p class="text-red-500 text-xs mt-2"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </ul>
            </form>
        </div>
    </div>
</main>

<?php require base_path('views/partials/footer.php'); ?>