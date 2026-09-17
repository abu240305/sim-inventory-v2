   <style>
      /* ============================================================
         HERO BANNER BBWS
         Struktur lapisan (bawah ke atas):
         0 foto → 1 scrim navy → 2 pita diagonal → 3 pita navy bawah
         → 4 karakter → 5 konten
         ============================================================ */
      .hero {
         --navy: #0a2246;
         --navy-deep: #06183a;
         --gold: #ffc72c;
         --band-h: 60px;
         /* tinggi pita navy paling bawah */
         position: relative;
         overflow: hidden;
         border: 0;
         border-radius: 1rem;
         background-color: var(--navy-deep);
         color: #fff;
      }

      .hero__photo {
         position: absolute;
         inset: 0;
         z-index: 0;
      }

      .hero__photo img {
         width: 100%;
         height: 100%;
         object-fit: cover;
         object-position: center 28%;
      }

      /* Elemen terpisah, bukan pseudo-element, supaya tidak bisa ditimpa tema */
      .hero__scrim {
         position: absolute;
         inset: 0;
         z-index: 1;
         background:
            linear-gradient(180deg, rgba(6, 24, 58, .55) 0%, rgba(6, 24, 58, 0) 45%),
            linear-gradient(96deg,
               rgba(6, 24, 58, .97) 0%,
               rgba(8, 28, 64, .93) 34%,
               rgba(10, 34, 70, .70) 50%,
               rgba(10, 34, 70, .25) 68%,
               rgba(10, 34, 70, .08) 100%);
      }

      /* Pita putih + garis emas yang menyapu diagonal */
      .hero__ribbon {
         position: absolute;
         left: 0;
         right: 0;
         bottom: calc(var(--band-h) - 26px);
         z-index: 2;
         line-height: 0;
         pointer-events: none;
      }

      .hero__ribbon svg {
         display: block;
         width: 100%;
         height: 78px;
      }

      /* Pita navy solid: alas untuk baris lokasi supaya selalu kontras */
      .hero__band {
         position: absolute;
         left: 0;
         right: 0;
         bottom: 0;
         z-index: 3;
         height: var(--band-h);
         background: var(--navy-deep);
      }

      .hero__character {
         position: absolute;
         right: 3%;
         bottom: calc(var(--band-h) - 14px);
         z-index: 4;
         height: 280px;
         object-fit: contain;
         object-position: bottom;
         pointer-events: none;
      }

      .hero__body {
         position: relative;
         z-index: 5;
         display: flex;
         flex-direction: column;
         min-height: 420px;
         padding: 1.25rem 1.5rem calc(var(--band-h) + 1.25rem);
      }

      /* ---- Identitas instansi ---- */
      .hero__logo {
         flex: 0 0 auto;
         width: 48px;
         padding: 4px;
         background: #fff;
         border-radius: 8px;
      }

      /* Hapus padding/background di atas kalau logo sudah PNG transparan */
      .hero__logo img {
         display: block;
         width: 100%;
         height: auto;
      }

      .hero__ministry {
         margin: 0;
         font-size: .85rem;
         font-weight: 800;
         line-height: 1.25;
         letter-spacing: .4px;
         text-transform: uppercase;
         color: #fff;
      }

      .hero__ministry small {
         display: block;
         margin-top: .15rem;
         font-size: .65rem;
         font-weight: 600;
         letter-spacing: 1.8px;
         color: rgba(255, 255, 255, .78);
      }

      .hero__unit {
         margin: 0.5rem 0 0;
         font-size: .85rem;
         font-weight: 800;
         line-height: 1.4;
         letter-spacing: .6px;
         text-transform: uppercase;
         color: #fff;
      }

      .hero__unit small {
         display: block;
         margin-top: .25rem;
         font-size: .65rem;
         font-weight: 600;
         letter-spacing: 5px;
         color: rgba(255, 255, 255, .78);
      }

      .hero__rule {
         width: 58px;
         height: 3px;
         margin: 0.75rem 0 1rem;
         background: var(--gold);
         border-radius: 2px;
      }

      /* ---- Tagline tulisan tangan ---- */
      .hero__tagline {
         font-family: 'Segoe Script', 'Brush Script MT', 'Snell Roundhand', cursive;
         font-size: 1.1rem;
         font-weight: 600;
         line-height: 1.2;
         text-align: right;
         color: #fff;
         text-shadow: 0 2px 10px rgba(6, 24, 58, .65);
      }

      .hero__tagline::after {
         content: '';
         display: block;
         width: 70%;
         height: 2px;
         margin: .4rem 0 0 auto;
         background: var(--gold);
         border-radius: 2px;
      }

      .hero__sigap {
         width: 100px;
      }

      .hero__sigap img {
         display: block;
         width: 100%;
         height: auto;
      }

      /* ---- Judul & ajakan ---- */
      .hero__title {
         margin: 0 0 0.5rem;
         font-size: clamp(1.4rem, 2.2vw, 1.8rem);
         font-weight: 800;
         line-height: 1.18;
         color: #fff;
         text-shadow: 0 2px 16px rgba(6, 24, 58, .55);
      }

      .hero__title span {
         color: var(--gold);
      }

      .hero__lead {
         max-width: 40ch;
         margin-bottom: 1rem;
         font-size: .85rem;
         line-height: 1.6;
         color: rgba(255, 255, 255, .9);
      }

      .hero__btn {
         display: inline-flex;
         align-items: center;
         gap: .4rem;
         padding: .5rem 1rem;
         font-size: .85rem;
         font-weight: 700;
         border-radius: .5rem;
         transition: background-color .2s ease, border-color .2s ease;
      }

      .hero__btn--solid {
         background: var(--gold);
         border: 2px solid var(--gold);
         color: #0a2246;
      }

      .hero__btn--solid:hover {
         background: #ffd45c;
         border-color: #ffd45c;
         color: #0a2246;
      }

      .hero__btn--ghost {
         border: 2px solid rgba(255, 255, 255, .5);
         color: #fff;
      }

      .hero__btn--ghost:hover {
         background: rgba(255, 255, 255, .14);
         border-color: #fff;
         color: #fff;
      }

      /* ---- Kartu empat pilar ---- */
      .hero__pillars-wrap {
         margin-top: auto;
         padding-top: 1.5rem;
      }

      .hero__pillars {
         display: grid;
         grid-template-columns: repeat(4, minmax(0, 1fr));
         max-width: 620px;
         padding: 0.85rem .5rem;
         background: #fff;
         border-radius: .9rem;
         box-shadow: 0 .85rem 2.25rem rgba(3, 17, 40, .3);
      }

      .hero__pillar {
         position: relative;
         padding: 0 .5rem;
         text-align: center;
         color: #12264a;
      }

      .hero__pillar+.hero__pillar::before {
         content: '';
         position: absolute;
         top: 50%;
         left: 0;
         width: 1px;
         height: 36px;
         background: #e3e6ec;
         transform: translateY(-50%);
      }

      /* Tinggi tetap supaya label tetap sejajar walau satu ikon gagal dimuat */
      .hero__pillar-icon {
         display: flex;
         align-items: center;
         justify-content: center;
         height: 24px;
         color: #12264a;
      }

      .hero__pillar span {
         display: block;
         margin-top: .25rem;
         font-size: .55rem;
         font-weight: 800;
         line-height: 1.4;
         letter-spacing: .3px;
         text-transform: uppercase;
      }

      /* ---- Baris lokasi & slogan (di dalam pita navy) ---- */
      .hero__foot {
         position: absolute;
         left: 0;
         right: 0;
         bottom: 0;
         z-index: 5;
         display: flex;
         align-items: center;
         justify-content: space-between;
         gap: 1rem;
         height: var(--band-h);
         padding: 0 2.25rem;
      }

      .hero__pin {
         display: flex;
         flex: 0 0 auto;
         align-items: center;
         justify-content: center;
         width: 34px;
         height: 34px;
         background: var(--gold);
         border-radius: 50%;
         color: #0a2246;
      }

      .hero__place-title {
         font-size: .88rem;
         font-weight: 700;
         line-height: 1.35;
         color: #fff;
      }

      .hero__place-sub {
         font-size: .76rem;
         color: rgba(255, 255, 255, .72);
      }

      .hero__slogan {
         text-align: right;
         line-height: 1.55;
      }

      .hero__slogan span {
         display: block;
         font-size: .7rem;
         letter-spacing: 4px;
         text-transform: uppercase;
         color: rgba(255, 255, 255, .65);
      }

      .hero__slogan strong {
         display: block;
         font-size: .78rem;
         font-weight: 700;
         letter-spacing: 4px;
         text-transform: uppercase;
         color: #fff;
      }

      @media (max-width: 1399.98px) {
         .hero__character {
            height: 300px;
         }
      }

      @media (max-width: 1199.98px) {
         .hero__pillars {
            max-width: none;
         }
      }

      @media (max-width: 991.98px) {
         .hero__body {
            min-height: 0;
            padding: 1.75rem 1.5rem calc(var(--band-h) + 1.5rem);
         }
      }

      @media (max-width: 575.98px) {
         .hero {
            --band-h: 76px;
         }

         .hero__body {
            padding: 1.5rem 1.15rem calc(var(--band-h) + 1.25rem);
         }

         .hero__foot {
            padding: 0 1.15rem;
         }

         .hero__pillars-wrap {
            padding-top: 1.25rem;
         }

         .hero__pillars {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            row-gap: 1.1rem;
         }

         .hero__pillar:nth-child(3)::before {
            display: none;
         }
      }

      /* ============ KARTU STATISTIK ============ */
      .stat-card {
         border: 0;
         transition: transform .2s ease, box-shadow .2s ease;
      }

      .stat-card:hover {
         transform: translateY(-3px);
         box-shadow: 0 .75rem 1.5rem rgba(16, 24, 40, .1);
      }

      .stat-card__value {
         margin-bottom: .15rem;
         font-size: 1.6rem;
         font-weight: 700;
         line-height: 1.2;
      }

      @media (prefers-reduced-motion: reduce) {

         .stat-card,
         .hero__btn {
            transition: none;
         }

         .stat-card:hover {
            transform: none;
         }
      }
   </style>
