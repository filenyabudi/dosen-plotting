<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Matakuliah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MatakuliahController
 */
final class MatakuliahControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $matakuliahs = Matakuliah::factory()->count(3)->create();

        $response = $this->get(route('matakuliahs.index'));

        $response->assertOk();
        $response->assertViewIs('matakuliah.index');
        $response->assertViewHas('matakuliahs');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('matakuliahs.create'));

        $response->assertOk();
        $response->assertViewIs('matakuliah.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MatakuliahController::class,
            'store',
            \App\Http\Requests\MatakuliahStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $kode_mk = $this->faker->word();
        $nama_mk = $this->faker->text();
        $sks = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('matakuliahs.store'), [
            'kode_mk' => $kode_mk,
            'nama_mk' => $nama_mk,
            'sks' => $sks,
        ]);

        $matakuliahs = Matakuliah::query()
            ->where('kode_mk', $kode_mk)
            ->where('nama_mk', $nama_mk)
            ->where('sks', $sks)
            ->get();
        $this->assertCount(1, $matakuliahs);
        $matakuliah = $matakuliahs->first();

        $response->assertRedirect(route('matakuliah.index'));
    }


    #[Test]
    public function show_displays_view(): void
    {
        $matakuliah = Matakuliah::factory()->create();

        $response = $this->get(route('matakuliahs.show', $matakuliah));

        $response->assertOk();
        $response->assertViewIs('matakuliah.show');
        $response->assertViewHas('matakuliah');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $matakuliah = Matakuliah::factory()->create();

        $response = $this->get(route('matakuliahs.edit', $matakuliah));

        $response->assertOk();
        $response->assertViewIs('matakuliah.edit');
        $response->assertViewHas('matakuliah');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MatakuliahController::class,
            'update',
            \App\Http\Requests\MatakuliahUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $matakuliah = Matakuliah::factory()->create();
        $kode_mk = $this->faker->word();
        $nama_mk = $this->faker->text();
        $sks = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('matakuliahs.update', $matakuliah), [
            'kode_mk' => $kode_mk,
            'nama_mk' => $nama_mk,
            'sks' => $sks,
        ]);

        $matakuliah->refresh();

        $response->assertRedirect(route('matakuliah.index'));

        $this->assertEquals($kode_mk, $matakuliah->kode_mk);
        $this->assertEquals($nama_mk, $matakuliah->nama_mk);
        $this->assertEquals($sks, $matakuliah->sks);
    }
}
