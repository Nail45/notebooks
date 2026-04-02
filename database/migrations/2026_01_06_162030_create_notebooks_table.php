<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('notebooks', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('slug');
      $table->string('title')->nullable();
      $table->decimal('rating', 2, 1)->nullable();
      $table->decimal('price', 10, 2)->nullable();
      $table->string('view_title')->nullable();
      $table->string('release_date')->nullable();
      $table->string('line')->nullable();
      $table->string('manufacturer')->nullable();
      $table->string('operating_system')->nullable();
      $table->string('certification')->nullable();
      $table->string('platform_code_name')->nullable();
      $table->string('processor_series')->nullable();
      $table->string('processor_model')->nullable();
      $table->string('cores_count', 100)->nullable();
      $table->string('threads_count', 100)->nullable();
      $table->string('clock_speed')->nullable();
      $table->string('turbo_frequency')->nullable();
      $table->string('cache_size')->nullable();
      $table->string('processor_tdp')->nullable();
      $table->float('screen_diagonal')->nullable();
      $table->string('screen_aspect_ratio')->nullable();
      $table->string('screen_resolution')->nullable();
      $table->string('refresh_rate')->nullable();
      $table->string('screen_surface')->nullable();
      $table->string('screen_technology')->nullable();
      $table->string('brightness')->nullable();
      $table->string('touch_screen', 100)->nullable();
      $table->string('ram_type')->nullable();
      $table->string('ram_capacity')->nullable();
      $table->string('ram_frequency')->nullable();
      $table->string('max_ram_capacity')->nullable();
      $table->string('memory_slots', 100)->nullable();
      $table->string('gpu_type')->nullable();
      $table->string('gpu_model')->nullable();
      $table->string('gpu_memory')->nullable();
      $table->string('storage_config')->nullable();
      $table->string('ssd_capacity')->nullable();
      $table->string('ssd_interface')->nullable();
      $table->string('sd_card_slot', 100)->nullable();
      $table->string('optical_drive', 100)->nullable();
      $table->string('ethernet_lan')->nullable();
      $table->string('wifi')->nullable();
      $table->string('bluetooth')->nullable();
      $table->string('usb_a_ports', 100)->nullable();
      $table->string('usb_c_ports', 100)->nullable();
      $table->string('total_usb_ports', 100)->nullable();
      $table->string('thunderbolt', 100)->nullable();
      $table->string('vga_port', 100)->nullable();
      $table->string('hdmi_port', 100)->nullable();
      $table->string('displayport', 100)->nullable();
      $table->string('audio_jack')->nullable();
      $table->string('transformer', 100)->nullable();
      $table->string('case_material')->nullable();
      $table->string('case_color')->nullable();
      $table->string('case_surface')->nullable();
      $table->string('lid_material')->nullable();
      $table->string('lid_color')->nullable();
      $table->string('lid_surface')->nullable();
      $table->string('keyboard_backlight', 100)->nullable();
      $table->string('island_keyboard', 100)->nullable();
      $table->string('built_in_camera', 100)->nullable();
      $table->string('camera_pixels')->nullable();
      $table->string('microphone')->nullable();
      $table->string('speakers_count', 100)->nullable();
      $table->string('numeric_keypad', 100)->nullable();
      $table->string('cyrillic_on_keyboard', 100)->nullable();
      $table->string('trackpad', 100)->nullable();
      $table->string('joystick_touchstick', 100)->nullable();
      $table->string('energy_reserve')->nullable();
      $table->string('packaging')->nullable();
      $table->string('width')->nullable();
      $table->string('depth')->nullable();
      $table->string('thickness')->nullable();
      $table->string('weight')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('notebooks');
  }
};
