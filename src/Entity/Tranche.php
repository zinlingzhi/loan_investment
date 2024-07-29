<?php

namespace App\Entity;

use App\Repository\TrancheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrancheRepository::class)]
class Tranche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $monthlyInterestRate = 0.0;

    #[ORM\Column]
    private ?float $maxAmount = 0.0;

    #[ORM\Column]
    private ?float $investedAmunt = 0.0;

    #[ORM\ManyToOne(inversedBy: 'investments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Loan $Loan = null;

    #[ORM\OneToMany(mappedBy: 'tranche', targetEntity: Investment::class)]
    private Collection $investments;

    public function __construct()
    {
        $this->investments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMonthlyInterestRate(): ?float
    {
        return $this->monthlyInterestRate;
    }

    public function setMonthlyInterestRate(float $monthlyInterestRate): static
    {
        $this->monthlyInterestRate = $monthlyInterestRate;

        return $this;
    }

    public function getMaxAmount(): ?float
    {
        return $this->maxAmount;
    }

    public function setMaxAmount(float $maxAmount): static
    {
        $this->maxAmount = $maxAmount;

        return $this;
    }

    public function getInvestedAmunt(): ?float
    {
        return $this->investedAmunt;
    }

    public function setInvestedAmunt(float $investedAmunt): static
    {
        $this->investedAmunt = $investedAmunt;

        return $this;
    }

    public function getLoan(): ?Loan
    {
        return $this->Loan;
    }

    public function setLoan(?Loan $Loan): static
    {
        $this->Loan = $Loan;

        return $this;
    }

    /**
     * @return Collection<int, Investment>
     */
    public function getInvestments(): Collection
    {
        return $this->investments;
    }

    public function addInvestment(Investment $investment): static
    {
        if (!$this->investments->contains($investment)) {
            $this->investments->add($investment);
            $investment->setTranche($this);
        }

        return $this;
    }

    public function removeInvestment(Investment $investment): static
    {
        if ($this->investments->removeElement($investment)) {
            // set the owning side to null (unless already changed)
            if ($investment->getTranche() === $this) {
                $investment->setTranche(null);
            }
        }

        return $this;
    }
}
