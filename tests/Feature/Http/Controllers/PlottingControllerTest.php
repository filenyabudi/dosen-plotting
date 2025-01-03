<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Plotting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PlottingController
 */
final class PlottingControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $plottings = Plotting::factory()->count(3)->create();

        $response = $this->get(route('plottings.index'));

        $response->assertOk();
        $response->assertViewIs('plotting.index');
        $response->assertViewHas('plottings');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('plottings.create'));

        $response->assertOk();
        $response->assertViewIs('plotting.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PlottingController::class,
            'store',
            \App\Http\Requests\PlottingStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $dosen = Dosen::factory()->create();
        $matakuliah = Matakuliah::factory()->create();
        $kelas = $this->faker->word();
        $semester = $this->faker->word();
        $tahun = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('plottings.store'), [
            'dosen_id' => $dosen->id,
            'matakuliah_id' => $matakuliah->id,
            'kelas' => $kelas,
            'semester' => $semester,
            'tahun' => $tahun,
        ]);

        $plottings = Plotting::query()
            ->where('dosen_id', $dosen->id)
            ->where('matakuliah_id', $matakuliah->id)
            ->where('kelas', $kelas)
            ->where('semester', $semester)
            ->where('tahun', $tahun)
            ->get();
        $this->assertCount(1, $plottings);
        $plotting = $plottings->first();

        $response->assertRedirect(route('plotting.index'));
    }


    #[Test]
    public function show_displays_view(): void
    {
        $plotting = Plotting::factory()->create();

        $response = $this->get(route('plottings.show', $plotting));

        $response->assertOk();
        $response->assertViewIs('plotting.show');
        $response->assertViewHas('plotting');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $plotting = Plotting::factory()->create();

        $response = $this->get(route('plottings.edit', $plotting));

        $response->assertOk();
        $response->assertViewIs('plotting.edit');
        $response->assertViewHas('plotting');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PlottingController::class,
            'update',
            \App\Http\Requests\PlottingUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $plotting = Plotting::factory()->create();
        $dosen = Dosen::factory()->create();
        $matakuliah = Matakuliah::factory()->create();
        $kelas = $this->faker->word();
        $semester = $this->faker->word();
        $tahun = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('plottings.update', $plotting), [
            'dosen_id' => $dosen->id,
            'matakuliah_id' => $matakuliah->id,
            'kelas' => $kelas,
            'semester' => $semester,
            'tahun' => $tahun,
        ]);

        $plotting->refresh();

        $response->assertRedirect(route('plotting.index'));

        $this->assertEquals($dosen->id, $plotting->dosen_id);
        $this->assertEquals($matakuliah->id, $plotting->matakuliah_id);
        $this->assertEquals($kelas, $plotting->kelas);
        $this->assertEquals($semester, $plotting->semester);
        $this->assertEquals($tahun, $plotting->tahun);
    }
}
