 <header
     class="sticky top-0 z-30 border-b border-slate-200 bg-teal-800 backdrop-blur-xl h-20"
 >
     <div class="flex flex-col justify-center h-full gap-4 px-4 py-0 sm:px-6 lg:px-8 text-white">
         <div class="flex items-center justify-between gap-4">
             <!-- Left -->
             @include('layouts.partials.left-nav', ['title' => 'Dashboard'])

             <!-- Right -->
             @include('layouts.partials.right-nav')
         </div>
     </div>
 </header>

 <!-- Mobile Search: now below the topbar -->
 <div class="md:hidden px-4 pt-2 bg-transparent">
     <div class="relative">
         <span
             class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-teal-700"
         >
             <svg
                 xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8"
             >
                 <path
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"
                 />
             </svg>
         </span>
         <input
             type="text"
             placeholder="Search..."
             class="h-11 w-full rounded-2xl border-2 border-teal-700 bg-teal-100 pl-11 pr-4 text-sm text-teal-900 placeholder:text-teal-500 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
         />
     </div>
 </div>