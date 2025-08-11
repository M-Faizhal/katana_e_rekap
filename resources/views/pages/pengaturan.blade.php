@extends('layouts.dashboard')

@section('page-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan</h1>
    <p class="text-gray-600">Kelola preferensi dan konfigurasi sistem</p>
</div>

<!-- Settings Tabs -->
<div class="bg-white rounded-lg shadow-md">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6">
            <button class="py-4 px-2 border-b-2 border-red-500 text-red-600 font-medium text-sm">
                General
            </button>
            <button class="py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Users & Permissions
            </button>
            <button class="py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Security
            </button>
            <button class="py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Notifications
            </button>
            <button class="py-4 px-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Integration
            </button>
        </nav>
    </div>

    <!-- General Settings -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Company Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Perusahaan</h3>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                        <input type="text" value="PT. Kamil Trio Niaga (KATANA)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" value="info@katana.co.id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                        <input type="tel" value="+62 21 1234 5678" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">Jl. Sudirman No. 123, Jakarta Pusat 10110</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" value="https://katana.co.id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    </div>
                </form>
            </div>

            <!-- System Preferences -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Preferensi Sistem</h3>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            <option>Asia/Jakarta (WIB)</option>
                            <option>Asia/Makassar (WITA)</option>
                            <option>Asia/Jayapura (WIT)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            <option>Bahasa Indonesia</option>
                            <option>English</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format Mata Uang</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            <option>Rupiah (Rp)</option>
                            <option>US Dollar ($)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format Tanggal</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                            <option>DD/MM/YYYY</option>
                            <option>MM/DD/YYYY</option>
                            <option>YYYY-MM-DD</option>
                        </select>
                    </div>

                    <!-- Feature Toggles -->
                    <div class="space-y-3 pt-4">
                        <h4 class="text-md font-medium text-gray-800">Fitur</h4>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Enable Notifications</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Auto Backup</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">Maintenance Mode</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Reset
            </button>
            <button class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <!-- System Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">System Status</h3>
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Database</span>
                <span class="text-sm font-medium text-green-600">Online</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">API Server</span>
                <span class="text-sm font-medium text-green-600">Online</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Storage</span>
                <span class="text-sm font-medium text-green-600">75% Available</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Cache</span>
                <span class="text-sm font-medium text-green-600">Active</span>
            </div>
        </div>
    </div>

    <!-- Backup & Maintenance -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Backup & Maintenance</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600 mb-2">Last Backup</p>
                <p class="text-sm font-medium">11 Agustus 2024, 02:00</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-2">Next Scheduled</p>
                <p class="text-sm font-medium">12 Agustus 2024, 02:00</p>
            </div>
            <div class="pt-2">
                <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                    <i class="fas fa-download mr-2"></i>Manual Backup
                </button>
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
        <div class="space-y-3">
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium">Settings Updated</p>
                    <p class="text-xs text-gray-500">2 minutes ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium">User Login</p>
                    <p class="text-xs text-gray-500">15 minutes ago</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                <div>
                    <p class="text-sm font-medium">Backup Completed</p>
                    <p class="text-xs text-gray-500">2 hours ago</p>
                </div>
            </div>
        </div>
        <button class="w-full text-red-600 hover:text-red-800 text-sm font-medium mt-4">
            View All Logs <i class="fas fa-arrow-right ml-1"></i>
        </button>
    </div>
</div>

<!-- Security Overview -->
<div class="bg-white rounded-lg shadow-md mt-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Security Overview</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
                <h4 class="font-medium text-gray-800">SSL Certificate</h4>
                <p class="text-sm text-green-600 mt-1">Valid until Dec 2024</p>
            </div>

            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-lock text-blue-600 text-xl"></i>
                </div>
                <h4 class="font-medium text-gray-800">2FA Enabled</h4>
                <p class="text-sm text-blue-600 mt-1">85% users enrolled</p>
            </div>

            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-key text-yellow-600 text-xl"></i>
                </div>
                <h4 class="font-medium text-gray-800">API Keys</h4>
                <p class="text-sm text-yellow-600 mt-1">12 active keys</p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-history text-purple-600 text-xl"></i>
                </div>
                <h4 class="font-medium text-gray-800">Audit Trail</h4>
                <p class="text-sm text-purple-600 mt-1">Active monitoring</p>
            </div>
        </div>
    </div>
</div>
@endsection
