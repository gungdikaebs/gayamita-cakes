<?php
// Tentukan halaman aktif
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

?>

<nav id="navbar" class="sticky top-0 z-50 transition-all duration-300 bg-transparent px-2">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <!-- Mobile menu button-->
                <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-indigo-600 focus:outline-2 focus:-outline-offset-1 focus:outline-indigo-500">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="flex flex-1 items-center justify-center sm:justify-between sm:items-stretch">
                <a href="index.php">
                    <div class="flex shrink-0 items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Gayamita Cake</h1>
                    </div>
                </a>
                <div class="hidden sm:ml-6 sm:flex justify-between">
                    <div class="flex space-x-4">
                        <a href="index.php"
                            class="rounded-md px-3 py-2 text-sm font-medium <?php echo $page == 'beranda' ? 'bg-primary/80 text-gray' : 'text-gray-900 hover:bg-primary/60 hover:text-gray'; ?>"
                            aria-current="<?php echo $page == 'beranda' ? 'page' : ''; ?>">Beranda</a>
                        <a href="index.php?page=tentang-kami"
                            class="rounded-md px-3 py-2 text-sm font-medium <?php echo $page == 'tentang-kami' ? 'bg-primary/80 text-gray' : 'text-gray-900 hover:bg-primary/60 hover:text-gray'; ?>">Tentang Kami</a>
                        <a href="index.php?page=produk"
                            class="rounded-md px-3 py-2 text-sm font-medium <?php echo $page == 'produk' ? 'bg-primary/80 text-gray' : 'text-gray-900 hover:bg-primary/60 hover:text-gray'; ?>">Produk</a>
                        <a href="index.php?page=kontak"
                            class="rounded-md px-3 py-2 text-sm font-medium <?php echo $page == 'kontak' ? 'bg-primary/80 text-gray' : 'text-gray-900 hover:bg-primary/60 hover:text-gray'; ?>">Kontak</a>
                    </div>

                    <div class="flex ml-14">
                        <!-- Ikon Keranjang -->
                        <a href="index.php?page=keranjang" class="relative inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.3h12.2a1 1 0 00.9-1.3L17 13M7 13V6h13" />
                            </svg>
                            <!-- <span id="cart-count" class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span> -->
                        </a>
                        <a href="https://wa.me/62881037714200"
                            class="ml-4 inline-flex items-center rounded-full border border-transparent bg-accent px-6 py-2 text-sm font-medium text-gray-900 shadow-sm hover:bg-accent/70 ">Pesan</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <el-disclosure id="mobile-menu" hidden class="block sm:hidden absolute top-16 left-0 w-full z-20 bg-white shadow-lg">
            <div class="space-y-1 px-4 pt-4 pb-4">
                <a href="index.php"
                    class="block rounded-md px-3 py-2 text-base font-medium <?php echo $page == 'beranda' ? 'bg-primary/80 text-gray-800' : 'text-gray-700 hover:bg-primary/60 hover:text-gray'; ?>"
                    aria-current="<?php echo $page == 'beranda' ? 'page' : ''; ?>">Beranda</a>
                <a href="index.php?page=tentang-kami"
                    class="block rounded-md px-3 py-2 text-base font-medium <?php echo $page == 'tentang-kami' ? 'bg-primary/80 text-gray-800' : 'text-gray-700 hover:bg-primary/60 hover:text-gray'; ?>">Tentang Kami</a>
                <a href="index.php?page=produk"
                    class="block rounded-md px-3 py-2 text-base font-medium <?php echo $page == 'produk' ? 'bg-primary/80 text-gray-800' : 'text-gray-700 hover:bg-primary/60 hover:text-gray'; ?>">Produk</a>
                <a href="index.php?page=kontak"
                    class="block rounded-md px-3 py-2 text-base font-medium <?php echo $page == 'kontak' ? 'bg-primary/80 text-gray-800' : 'text-gray-700 hover:bg-primary/60 hover:text-gray'; ?>">Kontak</a>
                <a href="index.php?page=keranjang"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-primary/60 hover:text-gray">
                    Keranjang
                    <span id="cart-count-mobile" class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                </a>
            </div>
            <div class="px-4 pb-4  border-gray-200 w-1/2 mx-auto">
                <a href="" class="block mt-2 rounded-lg bg-accent px-6 py-2 text-center font-medium text-gray-900 shadow-sm hover:bg-accent/70">Pesan</a>
            </div>
        </el-disclosure>
    </div>
</nav>