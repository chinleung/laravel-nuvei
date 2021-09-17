<?php

namespace ChinLeung\Nuvei;

use ChinLeung\Nuvei\Concerns\Makeable;

class Customer
{
    use Makeable;

    /**
     * The company name of the customer.
     *
     * @var string
     */
    protected $company;

    /**
     * The email of the customer.
     *
     * @var string
     */
    protected $email;

    /**
     * The first name of the customer.
     *
     * @var string
     */
    protected $firstName;

    /**
     * The id of the customer.
     *
     * @var string
     */
    protected $id;

    /**
     * The last name of the customer.
     *
     * @var string
     */
    protected $lastName;

    /**
     * The phone number of the customer.
     *
     * @var string
     */
    protected $phone;

    /**
     * Retrieve the email of the customer.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Retrieve the phone of the customer.
     *
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Retrieve the full name of the customer.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return "{$this->firstName} {$this->lastName}" ?: null;
    }

    /**
     * Set the company name of the customer.
     *
     * @param  string|null  $company
     * @return self
     */
    public function setCompany(?string $company): self
    {
        $this->company = substr($company, 0, 50);

        return $this;
    }

    /**
     * Set the email of the customer.
     *
     * @param  string|null  $email
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the first name of the customer.
     *
     * @param  string|null  $firstName
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Set the id of the customer.
     *
     * @param  string  $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the last name of the customer.
     *
     * @param  string|null  $lastName
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set the phone of the customer.
     *
     * @param  string|null  $phone
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Retrieve the information of the customer for a payload.
     *
     * @return array
     */
    public function toPayload(): array
    {
        return [];
    }
}
