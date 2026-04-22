 <div id="prospects-table" class="rounded-3xl border border-slate-200 bg-white shadow-sm">
          <div class="flex flex-col gap-3 border-b border-slate-200 p-5 mb-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <h3 class="text-lg font-bold text-slate-900">Prospect List</h3>
              <p class="text-sm text-slate-500">
                Query example: where user_id = 152
              </p>
            </div>
            <div class="flex gap-3">
              <button class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Export This User
              </button>
              <button class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
                Advanced Filters
              </button>
            </div>
          </div>

          <!-- Search and Filter Form -->
          <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center px-5" action="{{ url()->current() }}#prospects-table">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search prospects..." class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
            <select name="stage" class="rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
              <option value="">All Stages</option>
              <option value="Added" @if(request('stage')=='Added') selected @endif>Added</option>
              <option value="Contacted" @if(request('stage')=='Contacted') selected @endif>Contacted</option>
              <option value="Invited" @if(request('stage')=='Invited') selected @endif>Invited</option>
              <option value="Presented" @if(request('stage')=='Presented') selected @endif>Presented</option>
              <option value="Followed Up" @if(request('stage')=='Followed Up') selected @endif>Followed Up</option>
              <option value="Joined" @if(request('stage')=='Joined') selected @endif>Joined</option>
              <option value="Closed" @if(request('stage')=='Closed') selected @endif>Closed</option>
            </select>
            <select name="hotness" class="rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
              <option value="">Hotness</option>
              <option value="Hot" @if(request('hotness')=='Hot') selected @endif>Hot</option>
              <option value="Warm" @if(request('hotness')=='Warm') selected @endif>Warm</option>
              <option value="Cold" @if(request('hotness')=='Cold') selected @endif>Cold</option>
              <option value="Inactive" @if(request('hotness')=='Inactive') selected @endif>Inactive</option>
              <option value="N/A" @if(request('hotness')=='N/A') selected @endif>N/A</option>
            </select>
            <select name="source" class="rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
              <option value="">Sources</option>
              <option value="Facebook" @if(request('source')=='Facebook') selected @endif>Facebook</option>
              <option value="Referral" @if(request('source')=='Referral') selected @endif>Referral</option>
              <option value="YouTube" @if(request('source')=='YouTube') selected @endif>YouTube</option>
              <option value="Instagram" @if(request('source')=='Instagram') selected @endif>Instagram</option>
              <option value="TikTok" @if(request('source')=='TikTok') selected @endif>TikTok</option>
              <option value="Twitter" @if(request('source')=='Twitter') selected @endif>Twitter</option>
              <option value="LinkedIn" @if(request('source')=='LinkedIn') selected @endif>LinkedIn</option>
              <option value="Friend" @if(request('source')=='Friend') selected @endif>Friend</option>
              <option value="Family" @if(request('source')=='Family') selected @endif>Family</option>
              <option value="Other" @if(request('source')=='Other') selected @endif>Other</option>
            </select>
            <label class="text-xs text-slate-500">Next Follow-Up:</label>
            <input type="date" name="next_follow_up_start" value="{{ request('next_follow_up_start') }}" class="rounded-xl border border-slate-200 px-2 py-2 text-xs text-slate-700 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" placeholder="Start" />
            <span class="text-xs text-slate-500">to</span>
            <input type="date" name="next_follow_up_end" value="{{ request('next_follow_up_end') }}" class="rounded-xl border border-slate-200 px-2 py-2 text-xs text-slate-700 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" placeholder="End" />
            <button type="submit" class="rounded-xl bg-sky-600 px-2 py-1 text-sm font-semibold text-white hover:bg-sky-700">Search</button>
            @if(request('search') || request('source') || request('stage') || request('hotness') || request('next_follow_up_start') || request('next_follow_up_end'))
              <a href="{{ route('prospects.index') }}#prospects-table" class="rounded-xl border border-slate-200 px-2 py-1 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            @endif
          </form>

          <div class="overflow-x-auto">
            <table class="min-w-full text-left">
              <thead class="bg-slate-50">
                <tr class="text-xs uppercase tracking-wide text-slate-500">
                  <th class="px-5 py-4 font-semibold">Prospect</th>
                  <th class="px-5 py-4 font-semibold">Stage</th>
                  <th class="px-5 py-4 font-semibold">Source</th>
                  <th class="px-5 py-4 font-semibold">Hotness</th>
                  <th class="px-5 py-4 font-semibold">Next Follow-Up</th>
                  <th class="px-5 py-4 font-semibold">Last Action</th>
                  <th class="px-5 py-4 font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                @foreach($prospects as $prospect)
                <tr class="hover:bg-slate-50">
                  <td class="px-5 py-4">
                    <div>
                      <p class="font-semibold text-slate-900">{{ $prospect->first_name }} {{ $prospect->last_name }}</p>
                      <p class="text-xs text-slate-500">{{ $prospect->email }} @if($prospect->phone) • {{ $prospect->phone }} @endif</p>
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ $prospect->latest_stage ?? '-' }}</span>
                  </td>
                  <td class="px-5 py-4 text-sm text-slate-600">{{ $prospect->source }}</td>
                  <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                      <span class="text-sm font-semibold text-slate-700">{{ $prospect->hotness }}</span>
                    </div>
                  </td>
                  <td class="px-5 py-4 text-xs font-medium text-slate-700">
                    {{ $prospect->next_follow_up ? \Carbon\Carbon::parse($prospect->next_follow_up)->format('M d, Y h:i A') : '-' }}
                  </td>
                  <td class="px-5 py-4 text-sm text-slate-600">{{ $prospect->last_action }}</td>
                  <td class="px-5 py-4">
                    <div class="flex items-center gap-1">
                      <!-- View Icon Button -->
                      <button class="p-0 m-0 text-sky-600 hover:text-sky-800" style="background:none;" title="View">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                      <!-- Edit Icon Button -->
                      <button class="p-0 m-0 text-amber-500 hover:text-amber-700" style="background:none;" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m2-2l-6 6m2-2l6-6" />
                        </svg>
                      </button>
                      <!-- Follow-Up Calendar Icon Button -->
                      <button class="p-0 m-0 text-emerald-600 hover:text-emerald-800" style="background:none;" title="Follow-Up">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2" stroke="currentColor" fill="none" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
                @endforeach
                @if($prospects->isEmpty())
                <tr>
                  <td colspan="8" class="px-5 py-8 text-center text-slate-500">No prospects found.</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const form = document.querySelector('form[method=GET]');
              if (form) {
                form.addEventListener('submit', function() {
                  setTimeout(function() {
                    const table = document.getElementById('prospects-table');
                    if (table) table.scrollIntoView({ behavior: 'smooth', block: 'start' });
                  }, 100);
                });
              }
            });
          </script>
          <!-- Pagination Links -->
          <div class="px-5 py-4">
            {{ $prospects->links('pagination::tailwind') }}
          </div>
        </div>