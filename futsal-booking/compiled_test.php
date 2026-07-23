<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
        $kategoriDenah = [
            'standar' => [
                'label' => 'Standar',
                'icon' => '⚽',
                'badge' => 'bg-blue-500/20 text-blue-300 border-blue-400/30',
            ],
            'internasional' => [
                'label' => 'Internasional',
                'icon' => '🏆',
                'badge' => 'bg-red-500/20 text-red-300 border-red-400/30',
            ],
        ];
        $statusTampilan = [
            'tersedia' => [
                'label' => 'Tersedia',
                'card' => 'bg-green-500/10 border-green-400/50 hover:bg-green-500/20',
                'text' => 'text-green-300',
                'badge' => 'bg-green-500/20 text-green-200',
            ],
            'pending' => [
                'label' => 'Menunggu bayar',
                'card' => 'bg-amber-500/10 border-amber-400/50 opacity-80',
                'text' => 'text-amber-300',
                'badge' => 'bg-amber-500/20 text-amber-200',
            ],
            'dipesan' => [
                'label' => 'Dipesan',
                'card' => 'bg-red-500/10 border-red-400/50 opacity-80',
                'text' => 'text-red-300',
                'badge' => 'bg-red-500/20 text-red-200',
            ],
            'tutup' => [
                'label' => 'Ditutup',
                'card' => 'bg-neutral-500/10 border-neutral-400/40 opacity-60',
                'text' => 'text-neutral-300',
                'badge' => 'bg-neutral-500/20 text-neutral-200',
            ],
        ];
    ?>

    <div class="max-w-5xl mx-auto py-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div>
                <a href="<?php echo e(route('customer.lapangan.index')); ?>" class="inline-flex items-center gap-2 text-sm text-neutral-400 hover:text-white transition mb-2">
                    <span aria-hidden="true">←</span>
                    Kembali ke daftar lapangan
                </a>
                <h2 class="text-2xl sm:text-3xl font-bold text-white">Denah Lapangan</h2>
            </div>
            <a href="<?php echo e(route('customer.booking.create')); ?>" class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-neutral-900 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
                Buat Booking
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="bg-neutral-900 border border-white/10 backdrop-blur-xl rounded-2xl p-5 sm:p-6 mb-5">
            <form method="GET" action="<?php echo e(route('customer.lapangan.denah')); ?>" class="flex flex-wrap gap-4 items-end">
                <label class="block">
                    <span class="block text-xs font-semibold text-neutral-300 mb-2">Tanggal main</span>
                    <input type="date" name="tanggal" value="<?php echo e($tanggal); ?>" min="<?php echo e(now()->addDays(2)->toDateString()); ?>"
                           class="bg-neutral-950 border border-white/10 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                </label>
                <label class="block">
                    <span class="block text-xs font-semibold text-neutral-300 mb-2">Jam mulai</span>
                    <select name="jam" class="bg-neutral-950 border border-white/10 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        <?php $__currentLoopData = $jamOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jamOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($jamOption); ?>" <?php if($jam === $jamOption): echo 'selected'; endif; ?>><?php echo e($jamOption); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </label>
                <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-neutral-900 font-semibold px-5 py-2.5 rounded-xl text-sm transition">
                    Cek ketersediaan
                </button>
            </form>
        </div>

        <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs text-neutral-300 mb-5">
            <?php $__currentLoopData = $statusTampilan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $tampilan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full <?php echo e($tampilan['badge']); ?>" aria-hidden="true"></span>
                    <?php echo e($tampilan['label']); ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="bg-blue-500/10 border border-blue-400/30 rounded-xl px-4 py-3 text-sm text-blue-200 mb-6">
            Menampilkan status pada <strong><?php echo e($tanggalCarbon->isoFormat('dddd, D MMMM YYYY')); ?></strong> pukul <strong><?php echo e($jam); ?></strong>.
        </div>

        <div class="space-y-8">
            <?php $__currentLoopData = $kategoriDenah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $infoKategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $lapanganKategori = $lapangans->where('kategori', $kategori);
                ?>

                <?php if($lapanganKategori->isNotEmpty()): ?>
                    <section class="bg-neutral-900 border border-white/10 backdrop-blur-xl rounded-2xl p-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wide <?php echo e($infoKategori['badge']); ?>">
                                <?php echo e($infoKategori['label']); ?>

                            </span>
                            <span class="text-xs text-neutral-400"><?php echo e($lapanganKategori->count()); ?> lapangan</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                            <?php $__currentLoopData = $lapanganKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lapangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $status = $statusLapangan->get($lapangan->id, 'tersedia');
                                    $tampilan = $statusTampilan[$status];
                                ?>

                                <?php if($status === 'tersedia'): ?>
                                    <a href="<?php echo e(route('customer.lapangan.show', $lapangan)); ?>"
                                       class="border rounded-xl p-4 text-center transition focus:outline-none focus:ring-2 focus:ring-amber-400 <?php echo e($tampilan['card']); ?>">
                                <?php else: ?>
                                    <div class="border rounded-xl p-4 text-center <?php echo e($tampilan['card']); ?>">
                                <?php endif; ?>
                                    <div class="text-2xl mb-2" aria-hidden="true"><?php echo e($infoKategori['icon']); ?></div>
                                    <div class="font-bold text-sm <?php echo e($tampilan['text']); ?>"><?php echo e($lapangan->nama_lapangan); ?></div>
                                    <div class="text-xs text-neutral-400 mt-1 capitalize"><?php echo e($lapangan->jenis_lapangan); ?> · <?php echo e($lapangan->tipe_venue); ?></div>
                                    <span class="inline-block mt-3 px-2.5 py-1 rounded-full text-[11px] font-semibold <?php echo e($tampilan['badge']); ?>"><?php echo e($tampilan['label']); ?></span>
                                <?php if($status === 'tersedia'): ?>
                                    </a>
                                <?php else: ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </section>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <p class="text-center text-xs text-neutral-500 mt-6">
            Klik lapangan berstatus tersedia untuk melihat detail dan melanjutkan booking. Status dapat berubah sewaktu-waktu.
        </p>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
