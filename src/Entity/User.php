<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User extends Person
{
    /**
     * @var ArrayCollection<int, Insight>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Insight::class)]
    private Collection $insights;

    /**
     * @var ArrayCollection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class,)]
    private Collection $ownedReviews;

    /**
     * @var ArrayCollection<int, Review>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private Collection $reviews;

    /**
     * @var ArrayCollection<int, Reaction>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Reaction::class)]
    private Collection $reactions;

    /**
     * @var ArrayCollection<int, Bookmark>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Bookmark::class)]
    private Collection $bookmarks;

    /**
     * @var ArrayCollection<int, Bid>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Bid::class)]
    private Collection $bids;

    public function __construct()
    {
        parent::__construct();
        $this->insights = new ArrayCollection();
        $this->ownedReviews = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    /**
     * @return ArrayCollection<int, Insight>
     */
    public function getInsights(): Collection
    {
        return $this->insights;
    }

    public function addInsight(Insight $insight): self
    {
        if (! $this->insights->contains($insight)) {
            $this->insights[] = $insight;
            $insight->setOwner($this);
        }

        return $this;
    }

    public function removeInsight(Insight $insight): self
    {
        // set the owning side to null (unless already changed)
        if ($this->insights->removeElement($insight) && $insight->getOwner() === $this) {
            $insight->setOwner(null);
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Review>
     */
    public function getOwnedReviews(): Collection
    {
        return $this->ownedReviews;
    }

    public function addOwnedReview(Review $ownedReview): self
    {
        if (! $this->ownedReviews->contains($ownedReview)) {
            $this->ownedReviews[] = $ownedReview;
            $ownedReview->setAuthor($this);
        }

        return $this;
    }

    public function removeOwnedReview(Review $ownedReview): self
    {
        // set the owning side to null (unless already changed)
        if ($this->ownedReviews->removeElement($ownedReview) && $ownedReview->getAuthor() === $this) {
            $ownedReview->setAuthor(null);
        }

        return $this;
    }

    /**
     * @return ArrayCollection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (! $this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setPerson($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reviews->removeElement($review) && $review->getPerson() === $this) {
            $review->setPerson(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): self
    {
        if (! $this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setOwner($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getOwner() === $this) {
                $reaction->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (! $this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setOwner($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getOwner() === $this) {
                $bookmark->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (! $this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setOwner($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getOwner() === $this) {
                $bid->setOwner(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

}
