<!DOCTYPE html>
<html class="light" dir="rtl" lang="ar">
  <head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
        </style>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#D63384","background-light": "#f9fafb","background-dark": "#111827","text-light": "#1f2937","text-dark": "#f3f4f6","subtext-light": "#6b7280","subtext-dark": "#9ca3af","border-light": "#e5e7eb","border-dark": "#374151",},
            fontFamily: {
              "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
          },
        },
      }
    </script>
  </head>
  <body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center p-4 group/design-root overflow-hidden">
      <div class="absolute inset-0 z-[-1] bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCS88lLFphKsmD8-q6C7WtoS7-eJ6VQ61J-q6r2r4vlnkBaK8QlnFhN3aSGHzXaMJzmPlSCyUVXAJOiDadFcAzE-Um77ariRAM-1Zt_pnw5RvpIBu-RKPtMABSZhKBZxgomqomGsUMh15uw-cU_cQD8amYJwZjV-ai0KYwhSzPsrYbYR2x1A9s7YQy4JWf2hM9pEoJ9GGfRZAS-pv3bgTg67cAFyPw_p42fIcJ3YUkDlNbYoS0opUEMvpQkzBpftdkqFEK9w96W2uY');">
        <div class="absolute inset-0 bg-black/40 dark:bg-black/60"></div>
      </div>
      <div class="w-full max-w-md bg-white/80 dark:bg-background-dark/80 backdrop-blur-lg rounded-xl p-8 shadow-2xl">
        <div class="flex flex-col gap-8">
          <div class="text-center">
            <p class="text-3xl font-bold tracking-tight text-text-light dark:text-text-dark">مرحباً بعودتك</p>
            <p class="text-subtext-light dark:text-subtext-dark mt-2">قم بتسجيل الدخول للمتابعة إلى حسابك.</p>
          </div>
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          <form action="{{ route('tlogin') }}" class="flex flex-col gap-6" method="POST">
            @csrf 
            <div class="flex flex-col gap-4">
              <label class="flex flex-col flex-1">
                <p class="text-sm font-medium pb-2 text-text-light dark:text-text-dark">اسم المستخدم</p>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark h-12 placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-4 text-base font-normal shadow-sm" name="name" required placeholder="أدخل بريدك الإلكتروني"/>
              </label>
              <label class="flex flex-col flex-1">
                <p class="text-sm font-medium pb-2 text-text-light dark:text-text-dark">كلمة المرور</p>
                <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark h-12 placeholder:text-subtext-light dark:placeholder:text-subtext-dark px-4 text-base font-normal shadow-sm" placeholder="أدخل كلمة المرور" name="password" required type="password"/>
              </label>
            </div>
            <button class="flex items-center justify-center font-bold text-base h-12 px-6 py-3 rounded-lg bg-primary text-white hover:bg-opacity-90 transition-colors w-full mt-4 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="submit">تسجيل الدخول</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>