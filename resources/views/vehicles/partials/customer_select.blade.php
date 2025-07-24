  @push('styles')
      <style>
          /* Custom TomSelect Theme */
          .ts-wrapper {
              --ts-pr-600: #2563eb;
              /* Warna primary */
              --ts-pr-200: #93c5fd;
              --ts-option-radius: 0.375rem;
              /* rounded-md */
          }

          /* Wrapper dan Control */
          .ts-wrapper.single .ts-control {
              @apply bg-gray-700 border border-gray-600 text-gray-300;
              background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
              background-position: right 0.5rem center;
              background-repeat: no-repeat;
              background-size: 1.5em 1.5em;
          }

          /* Dropdown */
          .ts-dropdown,
          .ts-dropdown .active {
              background-color: rgb(57 65 81) !important;
              border-color: rgb(57 65 81) !important;
          }



          /* Option */
          .ts-dropdown .option {
              @apply text-gray-300 hover:bg-gray-600;
          }

          /* Selected Option */
          .ts-dropdown .active {
              @apply bg-gray-600 text-white;
          }

          .ts-control,
          .ts-control input {
              background-color: transparent !important;
              border: none !important;
              padding: 0px !important;
              color: white;
          }

          /* Input Search */
          .ts-control input {
              @apply bg-gray-700 text-gray-300 placeholder-gray-400;
          }

          /* Focus State */
          .ts-control.focus {
              @apply ring-2 ring-blue-500 border-blue-500;
          }

          /* Error State */
          .ts-wrapper.error .ts-control {
              @apply border-red-500;
          }

          /* Item Selected */
          .ts-wrapper .item {
              @apply bg-gray-600 text-gray-300 rounded;
          }

          /* Clear Button */
          .ts-wrapper .clear-button {
              @apply text-gray-400 hover:text-gray-300;
          }
      </style>
  @endpush
  <div>
      <label for="customer_search" class="block text-sm font-medium text-gray-300 mb-2">Pemilik <span
              class="text-red-500">*</span></label>
      <select id="customer_search" name="customer_id"
          class="mt-1 block w-full bg-gray-700 border {{ $errors->has('customer_id') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          required placeholder="Cari pelanggan...">
          @if (old('customer_id') && ($customer = \App\Models\Customer::find(old('customer_id'))))
              <option value="{{ $customer->id }}" selected>
                  {{ $customer->name }} - {{ $customer->phone }}
              </option>
          @endif
      </select>
      @error('customer_id')
          <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
      @enderror
      @if (isset($customer))
          <input type="hidden" name="data-customer-selected" data-customer="{{ $customer }}">
      @endif
  </div>
  @push('scripts')
      <script>
          document.addEventListener('DOMContentLoaded', function() {

              const customer = $('input[name="data-customer-selected"]').data('customer');

              // Inisialisasi TomSelect
              const customerSearch = new TomSelect('#customer_search', {
                  valueField: 'id',
                  labelField: 'text',
                  searchField: 'text',
                  create: false,
                  items: [customer.name ?? ''],
                  load: function(query, callback) {

                      var url = base_url + '/api/customers/search?q=' + encodeURIComponent(
                          query);
                      fetch(url)
                          .then(response => response.json())
                          .then(json => {

                              callback(json);
                          }).catch(() => {
                              callback();
                          });
                  },
                  render: {
                      option: function(item, escape) {
                          return `
                                <div class="flex items-center p-2 bg-gray-700 text-gray-400">
                                    <div class="ml-2">
                                        <div class="text-gray-300">${escape(item.text)}</div>
                                        <div class="text-xs text-gray-400">${escape(item.phone)}</div>
                                    </div>
                                </div>`;
                      },
                      item: function(item, escape) {
                          return `<div class="bg-gray-600 text-gray-300 px-2 py-1 rounded">${escape(item.text)}</div>`;
                      },
                      no_results: function(data, escape) {

                          return `<div class="p-2 text-gray-400">Tidak ditemukan pelanggan dengan "${escape(data.input)}"</div>`;
                      },
                      option_create: function(data, escape) {
                          return `<div class="create p-2 text-gray-400 hover:bg-gray-600">Tambah baru: <strong>${escape(data.input)}</strong></div>`;
                      }
                  },
                  onInitialize: function() {
                      // Tambahkan class error jika ada validasi error
                      if (this.input.classList.contains('border-red-500')) {
                          this.wrapper.classList.add('error');
                      }
                  }
              });

              const customerSelect = document.getElementById('customer_search');
              if (customerSelect.classList.contains('border-red-500')) {
                  select.wrapper.classList.add('error');
              }
          });
      </script>
  @endpush
