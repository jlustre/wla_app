<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function how()
    {
        $stepContents = [
            1 => '<div class="rounded-lg border border-sky-400/20 bg-slate-950 text-slate-200 shadow-2xl"><div class="border-b border-white/10 px-6 py-5 sm:px-8"><div class="flex items-start justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Step 01</p><h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Join the Alliance</h3></div></div></div><div class="px-6 py-6 sm:px-8 sm:py-8"><div class="rounded-2xl border border-sky-400/15 bg-sky-500/10 p-4"><p class="text-sm leading-7 text-slate-200">Wealth Legacy Alliance is the starting point of the entire system. Instead of joining one company and building around only that one opportunity, you begin by joining WLA itself. This gives you access to a platform designed to help you build a single connected network that can later interact with multiple affiliated companies.</p></div><div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base"><p>To become a member of WLA, you must be invited by one of the active members of the alliance. That active member will share an invitation link with you, and using that invitation link, you will be redirected to the WLA registration page. The person who invited you then becomes your sponsor inside the WLA system.</p><p>This invitation-based structure helps establish the relationship between members from the very beginning. It ensures that every new member enters the alliance through a connected sponsor, making the network structure clear and organized right from registration.</p><p>The purpose of joining WLA first is to create your foundation. It becomes your central home base. From there, you are no longer forced to restart every time a new opportunity comes along. You are entering a system built for leverage, flexibility, and long-term growth.</p><p>This is important because many people spend years building in one company, only to discover later that they have to start over when they want to expand into something else. WLA solves that problem by giving you one place where your network begins and where your structure is tracked.</p><p>In simple terms, WLA is not just another company to join. It is the alliance that connects your efforts, your people, and your future opportunities into one organized system.</p></div></div></div>',
            2 => '<div class="rounded-lg border border-sky-400/20 bg-slate-950 text-slate-200 shadow-2xl">
  <div class="border-b border-white/10 px-6 py-5 sm:px-8">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Step 02</p>
    <h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Build Your Network Once</h3>
  </div>

  <div class="px-6 py-6 sm:px-8 sm:py-8">
    <div class="rounded-2xl border border-sky-400/15 bg-sky-500/10 p-4">
      <p class="text-sm leading-7 text-slate-200">
        Once you are inside WLA, you begin inviting people into the alliance itself. This is one of the
        biggest differences between WLA and the traditional way of building.
      </p>
    </div>

    <div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base">
      <p>
        Normally, if you want to grow in different companies, you have to recruit separately into each one.
        That means repeating the same process again and again.
      </p>

      <p>
        With WLA, your first goal is not to push everyone into one specific company. Your goal is to bring
        people into the alliance, where they become part of your overall network. This creates one unified
        downline instead of multiple disconnected teams.
      </p>

      <p>
        That means your effort becomes more efficient. Every person you introduce to WLA becomes part of
        something larger. Instead of building separate lists, separate structures, and separate teams,
        you are building one growing organization that can later connect to multiple opportunities.
      </p>

      <p>
        This approach reduces wasted effort and gives you a stronger long-term strategy. You are no longer
        working harder just to duplicate the same recruiting process in different places. You build once,
        and that network becomes the foundation for everything that follows.
      </p>
    </div>
  </div>
</div>',
            3 => '<div class="rounded-lg border border-sky-400/20 bg-slate-950 text-slate-200 shadow-2xl">
  <div class="border-b border-white/10 px-6 py-5 sm:px-8">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Step 03</p>
    <h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Track Your Downline in One Place</h3>
  </div>

  <div class="px-6 py-6 sm:px-8 sm:py-8">
    <div class="rounded-2xl border border-sky-400/15 bg-sky-500/10 p-4">
      <p class="text-sm leading-7 text-slate-200">
        One of the strongest benefits of WLA is centralized downline tracking. Everyone you personally
        bring into WLA is connected to you inside the WLA system.
      </p>
    </div>

    <div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base">
      <p>
        As your organization grows, WLA keeps that structure visible and organized in one place.
        This matters because in traditional networking models, people often lose track of who joined where,
        what team belongs to which company, and how their overall organization is growing.
      </p>

      <p>
        It becomes confusing, fragmented, and difficult to manage. WLA is designed to eliminate that confusion.
        Inside the platform, your network is not scattered across multiple disconnected systems.
      </p>

      <p>
        Instead, WLA gives you one central view of your organization. This allows you to understand your team
        growth more clearly, follow your structure more effectively, and maintain a better long-term strategy.
      </p>

      <p>
        This does not mean WLA replaces each company’s own compensation system. Each company still has its own
        records, plans, and payouts. But WLA gives you the benefit of seeing your larger network picture in one
        place, which helps protect your effort and gives you better visibility as you grow.
      </p>
    </div>
  </div>
</div>',
            4 => '<div class="rounded-lg border border-sky-400/20 bg-slate-950 text-slate-200 shadow-2xl">
  <div class="border-b border-white/10 px-6 py-5 sm:px-8">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Step 04</p>
    <h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Access Multiple Companies</h3>
  </div>

  <div class="px-6 py-6 sm:px-8 sm:py-8">
    <div class="rounded-2xl border border-sky-400/15 bg-sky-500/10 p-4">
      <p class="text-sm leading-7 text-slate-200">
        After building your network inside WLA, you gain access to multiple affiliated companies and
        opportunities within the alliance.
      </p>
    </div>

    <div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base">
      <p>
        This is one of the key advantages of the WLA model. Instead of being tied to only one company,
        one product, or one compensation structure, you gain access to several possible paths.
      </p>

      <p>
        This creates flexibility. Different people are interested in different things. Some may prefer
        insurance. Others may be interested in trading, health, supplements, or other categories.
        Because WLA connects to multiple companies, your network is not limited to just one option.
      </p>

      <p>
        This also creates protection. In business, no company is guaranteed to stay the same forever.
        A company may slow down, change leadership, change compensation, lose momentum, or even shut down.
      </p>

      <p>
        If all your effort is tied to only one basket, then one disruption can affect your entire income direction.
        WLA helps solve that problem by creating diversification. Since your network is already inside the alliance,
        you have access to multiple opportunities connected to that same network.
      </p>

      <p>
        This means you are not forced to begin again from zero if one company changes. It gives you a more stable
        and resilient strategy for long-term growth.
      </p>
    </div>
  </div>
</div>',
            5 => '<div class="rounded-lg border border-sky-400/20 bg-slate-950 text-slate-200 shadow-2xl">
  <div class="border-b border-white/10 px-6 py-5 sm:px-8">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Step 05</p>
    <h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Choose Which Company You Want to Join</h3>
  </div>

  <div class="px-6 py-6 sm:px-8 sm:py-8">
    <div class="rounded-2xl border border-sky-400/15 bg-sky-500/10 p-4">
      <p class="text-sm leading-7 text-slate-200">
        WLA gives you access to multiple affiliated companies, but it does not force you to join all of them.
        You decide which opportunities make sense for you.
      </p>
    </div>

    <div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base">
      <p>
        Your decision can be based on your interests, goals, timing, budget, and strategy. This freedom is
        important because not every opportunity will be the right fit for every person.
      </p>

      <p>
        Some people may want to focus on one company first. Others may want to position themselves in several
        companies. WLA gives you that choice without taking away your connection to the alliance.
      </p>

      <p>
        It is also important to understand the role of WLA here. WLA does not create or control the compensation
        plans of its affiliated companies. Each company has its own products, services, rules, compensation
        structure, and payout system.
      </p>

      <p>
        If you join a company, that company is responsible for how you earn within it. WLA’s role is different.
        WLA tracks your network structure across the alliance and shows how your downline connects to opportunities
        within the system.
      </p>

      <p>
        In other words, WLA is the structural and tracking platform, while each company remains responsible for its
        own compensation. This gives you both flexibility and clarity. You can choose where to participate while still
        benefiting from being part of one larger system.
      </p>
    </div>
  </div>
</div>',
            6 => '<div class="rounded-lg border border-amber-400/20 bg-slate-950 text-slate-200 shadow-2xl">
  <div class="border-b border-white/10 px-6 py-5 sm:px-8">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-300">Step 06</p>
    <h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Stay Positioned So You Don’t Miss Out</h3>
  </div>

  <div class="px-6 py-6 sm:px-8 sm:py-8">
    <div class="rounded-2xl border border-amber-400/15 bg-amber-500/10 p-4">
      <p class="text-sm leading-7 text-slate-200">
        This is one of the most important concepts in WLA, and it is what makes the model unique.
        WLA uses a “fear of loss” positioning system.
      </p>
    </div>

    <div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base">
      <p>
        The idea is simple: even though the people you bring into WLA are part of your network inside the alliance,
        your sponsorship inside a specific company depends on whether you personally joined that company.
      </p>

      <p>
        Here is how it works. Let’s say you join WLA and bring in 5 people directly. Those 5 people are part of
        your WLA downline. Later, some or all of them decide to join one of the affiliated companies inside the platform.
      </p>

      <p>
        If you already joined that specific company, then you remain their sponsor in that company. But if you did not
        join that company, then you lose sponsorship of those people in that company. Instead, they will be placed under
        the next qualified upline who is already active in that company.
      </p>

      <div class="rounded-2xl border border-amber-300/20 bg-slate-900/70 p-4">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">Example</p>
        <p class="mt-2 text-sm leading-7 text-slate-300">
          You invited 5 people into WLA. Later, they join Company B. If you are not active in Company B,
          you do not remain their sponsor there. Their sponsorship in that company moves to the next qualified
          active upline.
        </p>
      </div>

      <p>
        This is not meant to be pressure for the sake of pressure. It is meant to create awareness and smart positioning.
        It encourages you to think ahead and stay aligned with the opportunities your network is moving into.
      </p>

      <p>
        The key idea is this: inside WLA, they are still part of your network structure. But inside that particular company,
        sponsorship follows qualification and positioning. This creates motivation for members to stay active and strategically
        positioned in the opportunities their teams are choosing.
      </p>
    </div>
  </div>
</div>',
            7 => '<div class="rounded-lg border border-emerald-400/20 bg-slate-950 text-slate-200 shadow-2xl">
  <div class="border-b border-white/10 px-6 py-5 sm:px-8">
    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300">Step 07</p>
    <h3 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Grow With More Stability</h3>
  </div>

  <div class="px-6 py-6 sm:px-8 sm:py-8">
    <div class="rounded-2xl border border-emerald-400/15 bg-emerald-500/10 p-4">
      <p class="text-sm leading-7 text-slate-200">
        The long-term strength of WLA comes from the combination of unified network building, multiple opportunities,
        and strategic positioning.
      </p>
    </div>

    <div class="mt-6 space-y-5 text-sm leading-7 text-slate-300 sm:text-base">
      <p>
        When these work together, you are no longer depending on one company alone for all your effort and future income potential.
        This creates more stability for you and your team.
      </p>

      <p>
        If one affiliated company becomes less active, changes direction, or disappears, you are not left with nothing.
        Because your network exists inside WLA, you still have a structure connected to other opportunities in the alliance.
      </p>

      <p>
        That means your effort has a longer life. The people you brought into WLA are not tied only to one business path.
        They are part of a broader system. As long as there are active opportunities in the alliance, you still have ways
        to remain connected, positioned, and growing.
      </p>

      <p>
        This is one of the biggest reasons WLA is different from the traditional one-company approach. Instead of building
        something fragile, you are building something more flexible.
      </p>

      <p>
        Instead of relying on one path, you have multiple possible paths. Instead of starting over when things change,
        you can adapt and continue moving forward. In simple terms, WLA helps you build smarter, protect your effort,
        and create a business structure with more room to last.
      </p>
    </div>
  </div>
</div>',
        ];
        return view('welcome', ['stepContents' => $stepContents]);
    }
}
