<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DosenController
 */
final class DosenControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $dosens = Dosen::factory()->count(3)->create();

        $response = $this->get(route('dosens.index'));

        $response->assertOk();
        $response->assertViewIs('dosen.index');
        $response->assertViewHas('dosens');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('dosens.create'));

        $response->assertOk();
        $response->assertViewIs('dosen.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DosenController::class,
            'store',
            \App\Http\Requests\DosenStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $nama_lengkap = $this->faker->text();
        $nidn = $this->faker->word();

        $response = $this->post(route('dosens.store'), [
            'nama_lengkap' => $nama_lengkap,
            'nidn' => $nidn,
        ]);

        $dosens = Dosen::query()
            ->where('nama_lengkap', $nama_lengkap)
            ->where('nidn', $nidn)
            ->get();
        $this->assertCount(1, $dosens);
        $dosen = $dosens->first();

        $response->assertRedirect(route('dosen.index'));
    }


    #[Test]
    public function show_displays_view(): void
    {
        $dosen = Dosen::factory()->create();

        $response = $this->get(route('dosens.show', $dosen));

        $response->assertOk();
        $response->assertViewIs('dosen.show');
        $response->assertViewHas('dosen');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $dosen = Dosen::factory()->create();

        $response = $this->get(route('dosens.edit', $dosen));

        $response->assertOk();
        $response->assertViewIs('dosen.edit');
        $response->assertViewHas('dosen');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DosenController::class,
            'update',
            \App\Http\Requests\DosenUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $dosen = Dosen::factory()->create();
        $nama_lengkap = $this->faker->text();
        $nidn = $this->faker->word();

        $response = $this->put(route('dosens.update', $dosen), [
            'nama_lengkap' => $nama_lengkap,
            'nidn' => $nidn,
        ]);

        $dosen->refresh();

        $response->assertRedirect(route('dosen.index'));

        $this->assertEquals($nama_lengkap, $dosen->nama_lengkap);
        $this->assertEquals($nidn, $dosen->nidn);
    }
}
